<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $note->title }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $note->title }}
                    </h2>
                    <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
            </div>
        </header>

        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold mb-2">{{ $note->title }}</h1>
                                <div class="flex space-x-2 mb-4">
                                    @if ($note->group && $note->group->is_published)
                                        <a href="{{ route('groups.published', $note->group->slug) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $note->group->name }}
                                        </a>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ __('Published') }}: {{ $note->updated_at->format('F j, Y') }}</p>
                            </div>

                            @if ($note->image_path)
                                <div class="mb-6">
                                    <img src="{{ asset('storage/' . $note->image_path) }}" alt="{{ $note->title }}" class="max-w-full h-auto rounded-lg">
                                </div>
                            @endif

                            <div class="prose max-w-none">
                                {!! nl2br(e($note->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white shadow mt-auto">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('All rights reserved.') }}
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
