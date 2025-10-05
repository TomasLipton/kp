import fs from "fs";
import { WebSocketServer } from "ws";
import OpenAI from "openai";

const openai = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });
const wss = new WebSocketServer({ port: 3000 });

console.log("🚀 WebSocket server started on ws://localhost:3000");

wss.on("connection", (ws) => {
    console.log("Client connected");

    const chunks = [];
    let isProcessing = false;

    ws.on("message", async (msg, isBinary) => {
        if (!isBinary && msg.toString() === "END_RECORDING" && !isProcessing) {
            isProcessing = true;
            const startTime = Date.now();
            console.log("Processing audio...");
            const filePath = "./input.webm";
            fs.writeFileSync(filePath, Buffer.concat(chunks));

            try {
                // STT
                const stt = await openai.audio.transcriptions.create({
                    model: "gpt-4o-mini-transcribe",
                    file: fs.createReadStream(filePath),
                });
                const userText = stt.text;
                console.log("User said:", userText);

                // Send user message to client
                ws.send(JSON.stringify({
                    role: "user",
                    text: userText,
                }));

                const prompt = 'You are a helpful assistant.';

                // Chat
                const reply = await openai.chat.completions.create({
                    model: "gpt-5",
                    messages: [
                        { role: "system", content: prompt },
                        { role: "user", content: userText },
                    ],
                });

                const replyText = reply.choices[0].message.content;
                console.log("Assistant:", replyText);

                // TTS
                const speech = await openai.audio.speech.create({
                    model: "gpt-4o-mini-tts",
                    voice: "verse",
                    input: replyText,
                    response_format: "mp3",
                });

                const buffer = Buffer.from(await speech.arrayBuffer());
                const duration = Date.now() - startTime;

                // Send assistant response with audio and metadata
                ws.send(JSON.stringify({
                    role: "assistant",
                    text: replyText,
                    audio: buffer.toString('base64'),
                    duration: duration,
                }));

            } catch (err) {
                console.error("Error:", err);
            }
        } else {
            chunks.push(msg);
        }
    });

    ws.on("close", () => {
        console.log("Client disconnected");
    });
});
