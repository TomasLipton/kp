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
import OpenAI from "openai";
import {
    createQuizSession,
    getQuizSession,
    getConversationHistory,
    saveUserMessage,
    saveAssistantMessage,
    saveToolMessage,
    updateQuizConversationId
} from "./lib/database.js";
import { toolDefinitions, handleToolCall } from "./lib/tools.js";
import {transcribeAudio, generateChatResponse, generateSpeech, generateChatResponseForTools} from "./lib/openai-service.js";

const client = new OpenAI();
const wss = new WebSocketServer({ port: 6001 });

console.log("🚀 WebSocket server started on ws://localhost:6001");

wss.on("connection", (ws) => {
    console.log("Client connected");

    const chunks = [];
    let isProcessing = false;
    let conversationHistory = [];
    let quizSessionId = null;
    let conversationId = null;

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
                        const toolName = toolCall.function.name;
                        const toolArgs = JSON.parse(toolCall.function.arguments);

                        console.log(`Calling tool: ${toolName}`, toolArgs);

                        const toolResult = await handleToolCall(toolName, toolArgs);

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

                        // Create OpenAI conversation
                        const conversation = await client.conversations.create();
                        console.log("OpenAI Conversation created:", conversation);

                        // Store conversation ID
                        conversationId = conversation.id;

                        // Save conversation ID to database
                        await updateQuizConversationId(quizSessionId, conversation.id);
                        console.log("Conversation ID saved to database:", conversation.id);

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
