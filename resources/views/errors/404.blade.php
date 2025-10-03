<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('404.title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .bounce-animation {
            animation: bounce 2s ease-in-out infinite;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-purple-950 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <div class="text-center">
            <!-- 404 Illustration -->
            <div class="mb-8 relative">
                <div class="float-animation inline-block">
                    <svg class="w-64 h-64 mx-auto text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <!-- Floating particles -->
                <div class="absolute top-0 left-1/4 w-3 h-3 bg-purple-400 rounded-full opacity-60 bounce-animation" style="animation-delay: 0.2s;"></div>
                <div class="absolute bottom-10 right-1/4 w-2 h-2 bg-blue-400 rounded-full opacity-60 bounce-animation" style="animation-delay: 0.5s;"></div>
                <div class="absolute top-1/3 left-10 w-2 h-2 bg-pink-400 rounded-full opacity-60 bounce-animation" style="animation-delay: 0.8s;"></div>
            </div>

            <!-- Error Message -->
            <h1 class="text-8xl md:text-9xl font-extrabold gradient-text mb-4">404</h1>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">
                {{ __('404.heading') }}
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                {{ __('404.message') }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="{{ url('/') }}" class="group relative px-8 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('404.go_home') }}
                    </span>
                </a>
                <button onclick="window.history.back()" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-800 dark:text-white border-2 border-gray-300 dark:border-gray-600 rounded-xl font-semibold hover:border-purple-500 dark:hover:border-purple-400 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('404.go_back') }}
                    </span>
                </button>
            </div>

            <!-- Helpful Links -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                    {{ __('404.maybe_looking_for') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 p-4 bg-purple-50 dark:bg-gray-700 rounded-lg hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('404.homepage') }}</span>
                    </a>
                    <a href="{{ url('/topics') }}" class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-gray-700 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('404.topics') }}</span>
                    </a>
                    @auth
                    <a href="{{ url('/profile') }}" class="flex items-center gap-3 p-4 bg-green-50 dark:bg-gray-700 rounded-lg hover:bg-green-100 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('404.account') }}</span>
                    </a>
                    <a href="{{ url('/ai-quiz') }}" class="flex items-center gap-3 p-4 bg-pink-50 dark:bg-gray-700 rounded-lg hover:bg-pink-100 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('404.ai_quiz') }}</span>
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-sm text-gray-500 dark:text-gray-400">
                {{ __('404.error_code') }}
            </p>
        </div>
    </div>
</body>
</html>
