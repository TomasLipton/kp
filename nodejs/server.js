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

    ws.on("message", async (msg) => {
        if (msg.toString() === "END_RECORDING" && !isProcessing) {
            isProcessing = true;
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

                // Chat
                const reply = await openai.chat.completions.create({
                    model: "gpt-4o-mini",
                    messages: [
                        { role: "system", content: "You are a helpful assistant." },
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
                ws.send(buffer);

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
