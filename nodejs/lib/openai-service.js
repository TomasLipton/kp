import fs from "fs";
import OpenAI from "openai";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
dotenv.config({ path: path.join(__dirname, '..', '.env') });

const openai = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });

export async function transcribeAudio(filePath) {
    const stt = await openai.audio.transcriptions.create({
        model: "gpt-4o-mini-transcribe",
        file: fs.createReadStream(filePath),
    });

    return stt.text;
}

export async function generateChatResponse(messages, tools = null, conversationId = null) {
    // Convert messages to responses API format
    const input = messages
        .filter(msg => msg.role !== 'system') // System messages handled via instructions
        .map(msg => {
            // Handle tool responses with special format
            if (msg.role === 'tool' && msg.tool_call_id) {
                return {
                    type: 'function_call_output',
                    call_id: msg.tool_call_id,
                    output: msg.content
                };
            }

            // Regular messages
            return {
                type: 'message',
                role: msg.role,
                content: msg.content || ''
            };
        });

    const options = {
        model: "gpt-4.1",
        input: input,
    };

    // Add conversation ID if provided
    if (conversationId) {
        options.conversation = conversationId;
    }

    // Add instructions only on first message (when no conversationId exists)
    const systemMessage = messages.find(msg => msg.role === 'system');
    if (systemMessage && !conversationId) {
        options.instructions = systemMessage.content;
    }

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
