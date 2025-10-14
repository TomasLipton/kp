import { verifyQuizExists, saveAssistantMessage } from './database.js';

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

            // TODO: Implement logic to fetch the next question for this quiz session
            // This should return the next question from the database

            return {
                success: true,
                question: "Sample question placeholder",
                question_id: "sample_id"
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
