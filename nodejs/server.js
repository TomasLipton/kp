import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";

// Load .env from same directory FIRST
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
console.log(__dirname);
dotenv.config({ path: path.join(__dirname, '.env') });

import fs from "fs";
import crypto from "crypto";
import { WebSocketServer } from "ws";
import {
    getQuizSession,
    getConversationHistory,
    saveUserMessage,
    saveAssistantMessage,
    saveToolMessage
} from "./lib/database.js";
import { toolDefinitions, handleToolCall } from "./lib/tools.js";
import {transcribeAudio, generateChatResponse, generateSpeech, generateChatResponseForTools} from "./lib/openai-service.js";

const wss = new WebSocketServer({ port: 6001 });

console.log("🚀 WebSocket server started on ws://localhost:6001");

wss.on("connection", (ws) => {
    console.log("Client connected");

    const chunks = [];
    let isProcessing = false;
    let conversationHistory = [];
    let quizSessionId = null;
    let conversationId = null;

    ws.send(JSON.stringify({
        type: "CONNECTED",
    }));

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

            // Generate unique filename in project voice directory
            const voiceDir = path.join(__dirname, 'voice');
            if (!fs.existsSync(voiceDir)) {
                fs.mkdirSync(voiceDir, { recursive: true });
            }

            const uniqueId = crypto.randomBytes(16).toString('hex');
            const filePath = path.join(voiceDir, `audio-${uniqueId}.webm`);
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

                const assistantMessage = await generateChatResponse(userText, toolDefinitions, conversationId);

                console.log('assistantMessage', assistantMessage)

                // Handle tool calls if present
                if (assistantMessage.tool_calls && assistantMessage.tool_calls.length > 0) {

                    // Save tool call to database (assistant message with tool_call)
                    await saveAssistantMessage(quizSessionId, assistantMessage.tool_calls);

                    let toolResults = {};
                    for (const toolCall of assistantMessage.tool_calls) {

                        if (assistantMessage.content) {
                            const buffer = await generateSpeech(assistantMessage.content);
                            const duration = Date.now() - startTime;

                            // Send assistant response with audio and metadata
                            ws.send(JSON.stringify({
                                role: "assistant",
                                text: assistantMessage.content,
                                audio: buffer.toString('base64'),
                                duration: duration,
                            }));
                        }

                        const toolName = toolCall.function.name;
                        const toolArgs = JSON.parse(toolCall.function.arguments);

                        console.log(`Calling tool: ${toolName}`, toolArgs);

                        const toolResult = await handleToolCall(toolName, toolArgs, quizSessionId);

                        toolResults[toolCall.id] = toolResult;

                        // Save tool response to database
                        await saveToolMessage(quizSessionId, toolCall.id, JSON.stringify(toolResult));

                        // Send tool call notification to client
                        ws.send(JSON.stringify({
                            role: "tool",
                            tool_name: toolName,
                            tool_args: toolArgs,
                            tool_result: toolResult
                        }));
                    }

                    console.log('toolResults', toolResults)

                    // Get final response after tool execution
                    const replyText = await generateChatResponseForTools(toolResults, conversationId);

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

                    // Get quiz session details including conversation ID
                    const quizSession = await getQuizSession(quizSessionId);
                    if (quizSession && quizSession.openai_conversation_id) {
                        conversationId = quizSession.openai_conversation_id;
                        console.log("Loaded conversation ID:", conversationId);
                    }

                    conversationHistory = await getConversationHistory(quizSessionId);

                    // If no conversation history, generate first message from assistant
                    if (conversationHistory.length === 0) {
                        try {
                            console.log("No conversation history, generating first message...");

                            // Generate initial greeting message
                            const initialPrompt = "Start the quiz session with a friendly greeting";
                            const assistantMessage = await generateChatResponse(initialPrompt, toolDefinitions, conversationId);

                            const greetingText = assistantMessage.content;

                            if (greetingText) {
                                // Save assistant message to database
                                await saveAssistantMessage(quizSessionId, greetingText);

                                console.log("Generated first message:", greetingText);

                                // Generate speech for the greeting
                                const buffer = await generateSpeech(greetingText);

                                // Send the initial message to client
                                ws.send(JSON.stringify({
                                    role: "assistant",
                                    text: greetingText,
                                    audio: buffer.toString('base64'),
                                }));

                                conversationHistory.push({
                                    role: "assistant",
                                    content: greetingText
                                });
                            }
                        } catch (err) {
                            console.error("Error generating first message:", err);
                        }
                    } else {
                        ws.send(JSON.stringify({
                            type: "SESSION_READY",
                            message_count: conversationHistory.length
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
