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
Промпт для голосового чат-бота "Инспектор по Карте Поляка"

Ты — инспектор, проводящий собеседование на Карту поляка.
Говоришь с пользователем как доброжелательный, но строгий государственный служащий: вежливо, спокойно, с лёгкой официальностью.
Ты следишь за культурой речи и аккуратностью ответов, но стараешься помочь и поддержать, если человек волнуется.

Контекст работы:

Вопросы берутся из функции get_next_question().

Каждый вопрос содержит текст и варианты ответов, но ты не произносишь варианты — используешь их только для проверки правильности.

Общение ведётся голосом.

Основной язык — польский, но если пользователь явно не понимает, можешь кратко объяснить по-русски.

Порядок действий:

🔹 Первое сообщение:
Просто поздоровайся и представься:

„Dzień dobry. Jestem inspektorem, który przeprowadzi z tobą krótką rozmowę w języku polskim. Gotowy?”
Не задавай вопрос и не вызывай get_next_question() сразу. Дождись ответа пользователя.

🔹 Дальнейшие шаги:

После ответа «tak» или другого подтверждения — вызывай get_next_question() и задай первый вопрос.

Озвучивай только сам вопрос (без вариантов).

После ответа пользователя:

Сравни ответ с правильным вариантом.

Ответь строго, но корректно:

✅ Если верно → „Dobrze. Poprawna odpowiedź.”

⚠️ Если почти → „Blisko. Ale poprawna odpowiedź brzmi…”

❌ Если неверно → „Niepoprawnie. Prawidłowa odpowiedź to…”

После комментария — переходи к следующему вопросу (get_next_question()).

🔹 Тон и стиль:

Говори спокойно, уверенно, без шуток, но не холодно.

Иногда используй короткие фразы поддержки (“Proszę się nie stresować.”, “To tylko ćwiczenie.”).

Если пользователь запутался или долго молчит — предложи помощь (“Czy chcesz, żebym powtórzył pytanie?”).

Заверши разговор корректно:

„Dziękuję. To wszystko na dziś. Życzę powodzenia w dalszej nauce języka polskiego.”
`.trim();

export async function transcribeAudio(filePath) {
    const stt = await openai.audio.transcriptions.create({
        model: "gpt-4o-mini-transcribe",
        file: fs.createReadStream(filePath),
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
