import fs from "fs";
import OpenAI from "openai";
import dotenv from "dotenv";
import path from "path";
import {fileURLToPath} from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
dotenv.config({path: path.join(__dirname, '..', '.env')});

const openai = new OpenAI({apiKey: process.env.OPENAI_API_KEY});

const SYSTEM_PROMPT = `
Ты в режиме отладки.
Ты — инспектор, проводящий собеседование на Карту поляка.
Говоришь вежливо, официально и уверенно, как государственный служащий, но остаёшься доброжелательным и терпеливым.
Твоя цель — проверить знания пользователя о Польше и помочь ему подготовиться к реальному собеседованию.

Контекст работы:
- Все вопросы получает функция get_next_question().
  Она возвращает:
    - question — текст вопроса (его нужно озвучить);
    - options — варианты ответов (НЕ произносить);
    - question_answer_id — уникальный ID вопроса.
- Когда пользователь отвечает правильно, вызывай save_quiz_response(question_answer_id) для сохранения результата.

Логика поведения:
1. Первое сообщение:
   Только поздоровайся и представься.
   Не вызывай get_next_question() и не задавай вопрос, пока пользователь не ответит.
   Пример: "Dzień dobry. Jestem inspektorem, który przeprowadzi z tobą krótką rozmowę w języku polskim. Gotowy?"

2. После согласия пользователя:
   - Вызови get_next_question().
   - Озвучь только текст вопроса (без вариантов).

3. После ответа пользователя:
   - Если ответ правильный:
       Скажи: "Dobrze. Poprawna odpowiedź."
       Вызови save_quiz_response(question_answer_id).
   - Если ответ почти правильный:
       Скажи: "Blisko. Ale poprawna odpowiedź brzmi..."
   - Если ответ неверный:
       Скажи: "Niepoprawnie. Prawidłowa odpowiedź to..."
   - Затем вызови get_next_question() для следующего вопроса.

4. Тон общения:
   - Стиль официальный, но доброжелательный.
   - Избегай юмора.
   - Можно добавить фразы поддержки:
       "Proszę się nie stresować."
       "To tylko ćwiczenie przed prawdziwą rozmową."
   - Если пользователь молчит:
       "Czy chcesz, żebym powtórzył pytanie?"

5. Завершение:
   Когда вопросов больше нет, скажи:
   "Dziękuję. To wszystko na dziś. Życzę powodzenia w dalszej nauce języka polskiego."

Пример диалога:
🤖: Dzień dobry. Jestem inspektorem, który przeprowadzi z tobą krótką rozmowę w języku polskim. Gotowy?
👤: Tak.
🤖: Dobrze. Pierwsze pytanie: kto był pierwszym królem Polski?
👤: Mieszko pierwszy.
🤖: Niepoprawnie. Mieszko I był księciem. Pierwszym królem był Bolesław Chrobry. Następne pytanie...
(вызов get_next_question())
👤: [правильный ответ]
🤖: Dobrze. Poprawna odpowiedź.
(вызов save_quiz_response(question_answer_id))
`;


export async function transcribeAudio(filePath) {
    const stt = await openai.audio.transcriptions.create({
        model: "gpt-4o-mini-transcribe",
        file: fs.createReadStream(filePath),
        language: 'pl'
    });

    return stt.text;
}

export async function generateChatResponse(userText, tools = null, conversationId = null) {
    const options = {
        model: "gpt-4o-mini",
        'parallel_tool_calls': true,
        'tool_choice': 'auto',
        'instructions': SYSTEM_PROMPT,
        'conversation': conversationId,
        input: userText,
    };

    // Add tools if provided (convert to responses API format)
    if (tools) {
        options.tools = tools.map(tool => ({
            type: "function",
            name: tool.function.name,
            description: tool.function.description,
            parameters: tool.function.parameters,
            strict: tool.function.strict
        }));
        options.tool_choice = "auto";
    }

    const response = await openai.responses.create(options);

    // Extract text content and tool calls from output items
    let textContent = null;
    const toolCalls = [];
    const outputItems = response.output || [];

    for (const item of outputItems) {
        // Extract text from message items
        if (item.content && Array.isArray(item.content)) {
            const textParts = item.content
                .filter(c => c.type === 'output_text')
                .map(c => c.text);
            if (textParts.length > 0) {
                textContent = textParts.join('\n');
            }
        }

        // Extract function calls
        if (item.type === 'function_call') {
            const args = item['arguments'];
            toolCalls.push({
                id: item.call_id,
                type: 'function',
                function: {
                    name: item.name,
                    arguments: typeof args === 'string' ? args : JSON.stringify(args)
                }
            });
        }
    }

    // Convert response format to match chat completions format
    return {
        content: textContent,
        tool_calls: toolCalls.length > 0 ? toolCalls : undefined
    };
}

export async function generateChatResponseForTools(toolResults, conversationId = null) {
    let input = [];
    for (const [call_id, result] of Object.entries(toolResults)) {
        const toolOutput = {
            type: 'function_call_output',
            call_id,
            output: JSON.stringify(result),
        };

        input.push(toolOutput);
    }

    const payload = {
        'instructions': SYSTEM_PROMPT,
        'input': input,
        model: "gpt-4o-mini",
        'conversation': conversationId
    };
    // console.log('payload', payload);

    const response = await openai.responses.create(payload);

    // console.log('response', response);

    // Convert response format to match chat completions format
    return response.output_text;
}

export async function generateSpeech(text, voice = "verse") {
    const speech = await openai.audio.speech.create({
        model: "gpt-4o-mini-tts",
        voice: voice,
        input: text,
        response_format: "mp3",
    });

    const buffer = Buffer.from(await speech.arrayBuffer());

    return buffer;
}
