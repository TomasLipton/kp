import mysql from "mysql2/promise";
import crypto from "crypto";

const dbConfig = {
    host: process.env.DB_HOST || '127.0.0.1',
    user: process.env.DB_USERNAME || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_DATABASE || 'kp',
    port: '3318'
};

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

export async function saveAssistantMessage(quizId, toolCall ) {
    const connection = await mysql.createConnection(dbConfig);

    await connection.execute(
        `INSERT INTO chat_messages (a_i_quiz_id, role, content, tool_name, tool_call, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, NOW(), NOW())`,
        [quizId, 'assistant', toolCall, null, null]
    );

    await connection.end();
}

export async function saveToolMessage(quizId, toolCallId, content) {
    const connection = await mysql.createConnection(dbConfig);

    await connection.execute(
        `INSERT INTO chat_messages (a_i_quiz_id, role, content, metadata, created_at, updated_at)
         VALUES (?, ?, ?, ?, NOW(), NOW())`,
        [quizId, 'tool', content, JSON.stringify({ tool_call_id: toolCallId })]
    );

    await connection.end();
}

export async function getConversationHistory(quizId) {
    const connection = await mysql.createConnection(dbConfig);

    const [messages] = await connection.execute(
        'SELECT role, content, tool_name, tool_call, metadata FROM chat_messages WHERE a_i_quiz_id = ? ORDER BY created_at ASC',
        [quizId]
    );

    await connection.end();

    return messages.map(m => {
        const message = {
            role: m.role,
            content: m.content
        };

        // If this is an assistant message with a tool call
        if (m.role === 'assistant' && m.tool_name && m.tool_call) {
            const toolCallData = typeof m.tool_call === 'string' ? JSON.parse(m.tool_call) : m.tool_call;
            message.tool_calls = [{
                id: toolCallData.id,
                type: 'function',
                function: {
                    name: m.tool_name,
                    arguments: JSON.stringify(toolCallData.arguments)
                }
            }];
            // Tool call messages don't have content
            message.content = null;
        }

        // If this is a tool response message
        if (m.role === 'tool' && m.metadata) {
            const metadata = typeof m.metadata === 'string' ? JSON.parse(m.metadata) : m.metadata;
            message.tool_call_id = metadata.tool_call_id;
        }

        return message;
    });
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

export async function saveQuizAnswer(aiQuizId, questionAnswerId) {
    const connection = await mysql.createConnection(dbConfig);

    try {
        // Get the quiz_id from a_i_quizzes
        const [quizData] = await connection.execute(
            `SELECT quiz_id FROM a_i_quizzes WHERE id = ?`,
            [aiQuizId]
        );

        if (quizData.length === 0 || !quizData[0].quiz_id) {
            await connection.end();
            return false;
        }

        const quizId = quizData[0].quiz_id;

        // Insert the answer into quiz_answers table
        await connection.execute(
            `INSERT INTO quiz_answers (quiz_id, question_answer_id, created_at, updated_at)
             VALUES (?, ?, NOW(), NOW())`,
            [quizId, questionAnswerId]
        );

        await connection.end();
        return true;
    } catch (error) {
        await connection.end();
        throw error;
    }
}

export async function getNextQuestion(aiQuizId) {
    const connection = await mysql.createConnection(dbConfig);

    try {
        // Get the topic_id from a_i_quizzes -> quizzes -> topics
        const [quizData] = await connection.execute(
            `SELECT q.topics_id
             FROM a_i_quizzes aiq
             INNER JOIN quizzes q ON aiq.quiz_id = q.id
             WHERE aiq.id = ?`,
            [aiQuizId]
        );

        if (quizData.length === 0) {
            await connection.end();
            return null;
        }

        const topicId = quizData[0].topics_id;

        // Get a random question from the topic that hasn't been answered yet
        const [questions] = await connection.execute(
            `SELECT q.id, q.question_pl, q.question_type, q.picture
             FROM questions q
             WHERE q.topics_id = ?
             AND q.deleted_at IS NULL
             AND q.id NOT IN (
                 SELECT qa.question_answer_id
                 FROM quiz_answers qa
                 INNER JOIN a_i_quizzes aiq ON qa.quiz_id = aiq.quiz_id
                 WHERE aiq.id = ?
             )
             ORDER BY RAND()
             LIMIT 1`,
            [topicId, aiQuizId]
        );

        if (questions.length === 0) {
            await connection.end();
            return null;
        }

        const question = questions[0];

        // Get answers for this question
        const [answers] = await connection.execute(
            `SELECT id, text, picture, is_correct, \`order\`
             FROM question_answers
             WHERE question_id = ?
             AND deleted_at IS NULL
             ORDER BY \`order\` ASC`,
            [question.id]
        );

        await connection.end();

        console.log('questionsquestions', question);

        return {
            ...question,
            answers: answers
        };
    } catch (error) {
        await connection.end();
        throw error;
    }
}

