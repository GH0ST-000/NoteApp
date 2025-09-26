<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $group->name }} - {{ config('app.name', 'Laravel') }}</title>

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
                        {{ $group->name }}
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold mb-2">{{ $group->name }}</h1>
                                <p class="text-sm text-gray-500">{{ __('Published') }}: {{ $group->updated_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">{{ __('Published Notes') }}</h3>

                    @if ($notes->isEmpty())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <p>{{ __('No published notes in this group yet.') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($notes as $note)
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $note->is_pinned ? 'border-2 border-yellow-400' : '' }}">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold mb-2">{{ $note->title }}</h3>
                                            <div class="flex space-x-2">
                                                @if ($note->is_pinned)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ __('Pinned') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($note->content, 150) }}</p>

                                        @if ($note->image_path)
                                            <div class="mb-4">
                                                <img src="{{ asset('storage/' . $note->image_path) }}" alt="{{ $note->title }}" class="w-full h-32 object-cover rounded">
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center mt-4">
                                            <span class="text-sm text-gray-500">{{ $note->updated_at->diffForHumans() }}</span>
                                            <a href="{{ route('notes.published', ['slug' => $note->slug]) }}" class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md font-medium text-xs text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('View Full Note') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
