<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Notes App - Organize and Share Your Notes</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-blue-50 to-white text-gray-800 min-h-screen">
        <div class="relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-pink-100 rounded-full mix-blend-multiply filter blur-xl opacity-50"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-xl opacity-50"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

            <!-- Header -->
            <header class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <x-application-logo class="block h-10 w-auto" />
                        <span class="ml-3 text-xl font-bold text-gray-900">{{ config('app.name', 'Notes App') }}</span>
                    </div>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            <!-- Main Content -->
            <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Hero Section -->
                <div class="text-center mb-16">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                        <span class="block">Your thoughts, organized</span>
                        <span class="block text-blue-600">beautifully.</span>
                    </h1>
                    <p class="max-w-2xl mx-auto text-xl text-gray-600 mb-8">
                        Create, organize, and share your notes with ease. Keep your ideas safe and accessible from anywhere.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Log in
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Features Section -->
                <div class="py-12">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12">
                            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                                Everything you need to manage your notes
                            </h2>
                            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-600">
                                Simple, intuitive, and designed for productivity.
                            </p>
                        </div>

                        <div class="mt-10">
                            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                                <!-- Feature 1 -->
                                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-md">
                                    <div class="px-6 py-8">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600 mb-4">
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Create Notes</h3>
                                        <p class="text-base text-gray-600">
                                            Quickly capture your thoughts, ideas, and to-dos with our intuitive note editor.
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 2 -->
                                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-md">
                                    <div class="px-6 py-8">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600 mb-4">
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Organize in Groups</h3>
                                        <p class="text-base text-gray-600">
                                            Keep your notes organized by creating custom groups for different projects or topics.
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 3 -->
                                <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-md">
                                    <div class="px-6 py-8">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-pink-100 text-pink-600 mb-4">
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Share with Others</h3>
                                        <p class="text-base text-gray-600">
                                            Publish your notes or entire groups to share them with others via unique URLs.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
