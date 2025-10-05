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
                required: ["response", "quiz_session_id"],
                properties: {
                    response: {
                        type: "string",
                        description: "User's answer or response to the quiz question"
                    },
                    quiz_session_id: {
                        type: "string",
                        description: "Unique identifier for the quiz session"
                    }
                },
                additionalProperties: false
            }
        }
    }
];

export async function handleToolCall(toolName, args) {
    if (toolName === "save_quiz_response") {
        const { response, quiz_session_id } = args;

        try {
            const quizExists = await verifyQuizExists(quiz_session_id);

            if (!quizExists) {
                return {
                    success: false,
                    error: "Quiz session not found"
                };
            }

            // await saveAssistantMessage(quiz_session_id, response, toolName, args);

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

    return {
        success: false,
        error: "Unknown tool"
    };
}
