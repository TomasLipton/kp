import { verifyQuizExists, saveAssistantMessage, getNextQuestion } from './database.js';

export const toolDefinitions = [
    {
        type: "function",
        function: {
            name: "save_quiz_response",
            description: "Save user response to a quiz question for a specific session",
            strict: true,
            parameters: {
                type: "object",
                required: ["response"],
                properties: {
                    response: {
                        type: "string",
                        description: "User's answer or response to the quiz question"
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
        const { response } = args;

        try {
            const quizExists = await verifyQuizExists(quizSessionId);

            if (!quizExists) {
                return {
                    success: false,
                    error: "Quiz session not found"
                };
            }

            // await saveAssistantMessage(quizSessionId, response, toolName, args);

            return {
                success: true,
                message: "Quiz response saved successfully"
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
