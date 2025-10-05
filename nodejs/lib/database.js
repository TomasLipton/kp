import mysql from "mysql2/promise";
import crypto from "crypto";

const dbConfig = {
    host: process.env.DB_HOST || '127.0.0.1',
    user: process.env.DB_USERNAME || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_DATABASE || 'kp',
    port: '3318'
};

export async function createQuizSession(userId, topicId, options = {}) {
    const connection = await mysql.createConnection(dbConfig);

    const {
        type = 'conversation',
        speed = 'normal',
        difficulty = 'medium',
        gender = 'female',
        voice = 'verse',
        status = 'active'
    } = options;

    const uuid = crypto.randomUUID();

    const [result] = await connection.execute(
        `INSERT INTO a_i_quizzes (id, user_id, topic_id, type, speed, difficulty, gender, voice, status, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
        [uuid, userId, topicId, type, speed, difficulty, gender, voice, status]
    );

    await connection.end();

    return uuid;
}

export async function getQuizSession(quizId) {
    const connection = await mysql.createConnection(dbConfig);

    const [rows] = await connection.execute(
        'SELECT * FROM a_i_quizzes WHERE id = ?',
        [quizId]
    );

    await connection.end();

    return rows.length > 0 ? rows[0] : null;
}

export async function saveUserMessage(quizId, content) {
    const connection = await mysql.createConnection(dbConfig);

    await connection.execute(
        `INSERT INTO chat_messages (a_i_quiz_id, role, content, created_at, updated_at)
         VALUES (?, ?, ?, NOW(), NOW())`,
        [quizId, 'user', content]
    );

    await connection.end();
}

export async function saveAssistantMessage(quizId, content, toolName = null, toolCall = null) {
    const connection = await mysql.createConnection(dbConfig);

    await connection.execute(
        `INSERT INTO chat_messages (a_i_quiz_id, role, content, tool_name, tool_call, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, NOW(), NOW())`,
        [quizId, 'assistant', content, toolName, toolCall ? JSON.stringify(toolCall) : null]
    );

    await connection.end();
}

export async function getConversationHistory(quizId) {
    const connection = await mysql.createConnection(dbConfig);

    const [messages] = await connection.execute(
        'SELECT role, content FROM chat_messages WHERE a_i_quiz_id = ? ORDER BY created_at ASC',
        [quizId]
    );

    await connection.end();

    return messages.map(m => ({
        role: m.role,
        content: m.content
    }));
}

export async function verifyQuizExists(quizId) {
    const connection = await mysql.createConnection(dbConfig);

    const [rows] = await connection.execute(
        'SELECT id FROM a_i_quizzes WHERE id = ?',
        [quizId]
    );

    await connection.end();

    return rows.length > 0;
}
