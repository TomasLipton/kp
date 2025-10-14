import { verifyQuizExists, saveAssistantMessage, getNextQuestion, saveQuizAnswer } from './database.js';

export const toolDefinitions = [
    {
        type: "function",
        function: {
            name: "save_quiz_response",
            description: "Save user response to a quiz question for a specific session",
            strict: true,
            parameters: {
                type: "object",
                required: ["question_answer_id"],
                properties: {
                    question_answer_id: {
                        type: "integer",
                        description: "ID of the selected answer from the question_answers table"
                    }
                },
                additionalProperties: false
            }
        }
    },
    {
        type: "function",
        function: {
            name: "get_next_question",
            description: "Get the next question for a specific quiz session",
            strict: true,
            parameters: {
                type: "object",
                required: [],
                properties: {},
                additionalProperties: false
            }
        }
    }
];

export async function handleToolCall(toolName, args, quizSessionId) {
    if (toolName === "save_quiz_response") {
        const { question_answer_id } = args;

        try {
            const quizExists = await verifyQuizExists(quizSessionId);

            if (!quizExists) {
                return {
                    success: false,
                    error: "Quiz session not found"
                };
            }

            // Save the quiz answer to quiz_answers table
            const saved = await saveQuizAnswer(quizSessionId, question_answer_id);

            if (!saved) {
                return {
                    success: false,
                    error: "Failed to save quiz answer - quiz not found"
                };
            }

            return {
                success: true,
                message: "Quiz response saved successfully",
                question_answer_id: question_answer_id
            };
        } catch (error) {
            console.error("Error saving quiz response:", error);
            return {
                success: false,
                error: error.message
            };
        }
    }

    if (toolName === "get_next_question") {
        try {
            const quizExists = await verifyQuizExists(quizSessionId);

            if (!quizExists) {
                return {
                    success: false,
                    error: "Quiz session not found"
                };
            }

            const question = await getNextQuestion(quizSessionId);

            if (!question) {
                return {
                    success: false,
                    error: "No more questions available for this topic"
                };
            }

            return {
                success: true,
                question_id: question.id,
                question: question.question_pl,
                question_type: question.question_type,
                picture: question.picture,
                answers: question.answers.map(answer => ({
                    id: answer.id,
                    text: answer.text,
                    picture: answer.picture,
                    is_correct: answer.is_correct,
                    order: answer.order
                }))
            };
        } catch (error) {
            console.error("Error getting next question:", error);
            return {
                success: false,
                error: error.message
            };
        }
    }

    return {
        success: false,
        error: "Unknown tool"
    };
}
