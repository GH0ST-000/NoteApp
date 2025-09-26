<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ __('Notes') }}
            </h2>
            <a href="{{ route('notes.create') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('New Note') }}
            </a>
        </div>
    </x-slot>

    <div class="flex h-full">
        <!-- Notes List Sidebar -->
        <div class="w-64 border-r border-gray-200 flex flex-col">
            <!-- Search Bar -->
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                    </div>
                    <input type="text" placeholder="Search notes..." class="block w-full  pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                </div>
            </div>

            <!-- Notes List -->
            <div class="flex-1 overflow-y-auto">
                @if (session('success'))
                    <div class="m-3 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded text-sm" role="alert">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($notes->isEmpty())
                    <div class="p-4 text-center text-gray-500">
                        <p class="mb-2">{{ __('You have no notes yet.') }}</p>
                        <a href="{{ route('notes.create') }}" class="text-blue-500 hover:text-blue-700">
                            {{ __('Create your first note') }}
                        </a>
                    </div>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($notes as $note)
                            <li>
                                <a href="{{ route('notes.show', $note) }}" class="block hover:bg-gray-50 {{ request()->route('note') && request()->route('note')->id === $note->id ? 'bg-gray-100' : '' }}">
                                    <div class="px-4 py-3">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $note->title }}</h3>
                                            @if ($note->is_pinned)
                                                <svg class="h-4 w-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4 4v7.5a2.5 2.5 0 002.5 2.5H9v8.5L12 20l3 2.5V14h2.5a2.5 2.5 0 002.5-2.5V4H4z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($note->content), 100) }}</p>
                                        <div class="mt-1 flex items-center text-xs text-gray-400">
                                            <span>{{ $note->updated_at->format('M d, Y') }}</span>
                                            @if ($note->is_published)
                                                <span class="ml-2 inline-flex items-center">
                                                    <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                    </svg>
                                                    {{ __('Published') }}
                                                </span>
                                            @endif
                                            @if ($note->group)
                                                <span class="ml-2 inline-flex items-center">
                                                    <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                    {{ $note->group->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white">
            @if ($notes->isNotEmpty())
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium">{{ __('Select a note to view') }}</h3>
                        <p class="mt-1 text-sm">{{ __('Or create a new note to get started.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('New Note') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
