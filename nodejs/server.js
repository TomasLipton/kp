import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";

// Load .env from same directory FIRST
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
console.log(__dirname);
dotenv.config({ path: path.join(__dirname, '.env') });

import fs from "fs";
import { WebSocketServer } from "ws";
import {
    createQuizSession,
    getConversationHistory,
    saveUserMessage,
    saveAssistantMessage,
    saveToolMessage
} from "./lib/database.js";
import { toolDefinitions, handleToolCall } from "./lib/tools.js";
import { transcribeAudio, generateChatResponse, generateSpeech } from "./lib/openai-service.js";

const wss = new WebSocketServer({ port: 3000 });

console.log("🚀 WebSocket server started on ws://localhost:3000");

wss.on("connection", (ws) => {
    console.log("Client connected");

    const chunks = [];
    let isProcessing = false;
    let conversationHistory = [];
    let quizSessionId = null;

    ws.on("message", async (msg, isBinary) => {
        // Handle END_RECORDING signal
        if (!isBinary && msg.toString() === "END_RECORDING" && !isProcessing) {
            if (!quizSessionId) {
                ws.send(JSON.stringify({
                    type: "ERROR",
                    message: "Session not initialized. Send INIT_SESSION first."
                }));
                return;
            }

            isProcessing = true;
            const startTime = Date.now();
            console.log("Processing audio...");
            const filePath = "./input.webm";
            fs.writeFileSync(filePath, Buffer.concat(chunks));

            try {
                // STT
                const userText = await transcribeAudio(filePath);
                console.log("User said:", userText);

                // Save user message to database
                await saveUserMessage(quizSessionId, userText);

                // Send user message to client
                ws.send(JSON.stringify({
                    role: "user",
                    text: userText,
                }));

                // Add user message to conversation history
                conversationHistory.push({ role: "user", content: userText });

                const prompt = 'You are a helpful assistant. You are in test mode. Run tool to test on each message';

                // Chat with tools
                const chatMessages = [
                    { role: "system", content: prompt },
                    ...conversationHistory
                ];

                console.log(chatMessages);

                const assistantMessage = await generateChatResponse(chatMessages, toolDefinitions);

                // Handle tool calls if present
                if (assistantMessage.tool_calls && assistantMessage.tool_calls.length > 0) {
                    conversationHistory.push(assistantMessage);

                    for (const toolCall of assistantMessage.tool_calls) {
                        const toolName = toolCall.function.name;
                        const toolArgs = JSON.parse(toolCall.function.arguments);

                        console.log(`Calling tool: ${toolName}`, toolArgs);

                        const toolResult = await handleToolCall(toolName, toolArgs);

                        // Save tool call to database (assistant message with tool_call)
                        await saveAssistantMessage(quizSessionId, null, toolName, {
                            id: toolCall.id,
                            arguments: toolArgs
                        });

                        // Save tool response to database
                        await saveToolMessage(quizSessionId, toolCall.id, JSON.stringify(toolResult));

                        // Add tool result to conversation
                        conversationHistory.push({
                            role: "tool",
                            tool_call_id: toolCall.id,
                            content: JSON.stringify(toolResult)
                        });

                        // Send tool call notification to client
                        ws.send(JSON.stringify({
                            role: "tool",
                            tool_name: toolName,
                            tool_args: toolArgs,
                            tool_result: toolResult
                        }));
                    }

                    // Get final response after tool execution
                    const finalMessage = await generateChatResponse([
                        { role: "system", content: prompt },
                        ...conversationHistory
                    ]);

                    const replyText = finalMessage.content;
                    conversationHistory.push({ role: "assistant", content: replyText });

                    // Save assistant message to database
                    await saveAssistantMessage(quizSessionId, replyText);

                    console.log("Assistant:", replyText);

                    // TTS
                    const buffer = await generateSpeech(replyText);
                    const duration = Date.now() - startTime;

                    // Send assistant response with audio and metadata
                    ws.send(JSON.stringify({
                        role: "assistant",
                        text: replyText,
                        audio: buffer.toString('base64'),
                        duration: duration,
                    }));
                } else {
                    // No tool calls, proceed normally
                    const replyText = assistantMessage.content;
                    conversationHistory.push({ role: "assistant", content: replyText });

                    // Save assistant message to database
                    await saveAssistantMessage(quizSessionId, replyText);

                    console.log("Assistant:", replyText);

                    // TTS
                    const buffer = await generateSpeech(replyText);
                    const duration = Date.now() - startTime;

                    // Send assistant response with audio and metadata
                    ws.send(JSON.stringify({
                        role: "assistant",
                        text: replyText,
                        audio: buffer.toString('base64'),
                        duration: duration,
                    }));
                }

            } catch (err) {
                console.error("Error:", err);
            } finally {
                isProcessing = false;
                chunks.length = 0;
            }
            return;
        }

        // Handle JSON messages (session setup)
        if (!isBinary) {
            try {
                const data = JSON.parse(msg.toString());

                // Initialize existing session
                if (data.type === "INIT_SESSION" && data.quiz_session_id) {
                    quizSessionId = data.quiz_session_id;
                    console.log("Session initialized:", quizSessionId);

                    conversationHistory = await getConversationHistory(quizSessionId);

                    ws.send(JSON.stringify({
                        type: "SESSION_READY",
                        message_count: conversationHistory.length
                    }));
                    return;
                }

                // Create new session
                if (data.type === "CREATE_SESSION" && data.user_id && data.topic_id) {
                    console.log("CREATE_SESSION request received:", data);
                    try {
                        quizSessionId = await createQuizSession(data.user_id, data.topic_id, data.options || {});
                        console.log("New session created:", quizSessionId);

                        ws.send(JSON.stringify({
                            type: "SESSION_CREATED",
                            quiz_session_id: quizSessionId
                        }));
                    } catch (err) {
                        console.error("Error creating session:", err);
                        ws.send(JSON.stringify({
                            type: "ERROR",
                            message: err.message
                        }));
                    }
                    return;
                }
            } catch (e) {
                // Not JSON, ignore
            }
        }

        // Handle audio chunks
        chunks.push(msg);
    });

    ws.on("close", () => {
        console.log("Client disconnected");
    });
});
