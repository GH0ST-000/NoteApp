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
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search notes..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                </div>
            </div>

            <!-- Notes List -->
            <div class="flex-1 overflow-y-auto">
                @if (session('success'))
                    <div class="m-3 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded text-sm" role="alert">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <ul class="divide-y divide-gray-200">
                    @foreach (Auth::user()->notes()->orderBy('is_pinned', 'desc')->orderBy('updated_at', 'desc')->get() as $noteItem)
                        <li>
                            <a href="{{ route('notes.show', $noteItem) }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-3">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $noteItem->title }}</h3>
                                        @if ($noteItem->is_pinned)
                                            <svg class="h-4 w-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 4v7.5a2.5 2.5 0 002.5 2.5H9v8.5L12 20l3 2.5V14h2.5a2.5 2.5 0 002.5-2.5V4H4z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($noteItem->content), 100) }}</p>
                                    <div class="mt-1 flex items-center text-xs text-gray-400">
                                        <span>{{ $noteItem->updated_at->format('M d, Y') }}</span>
                                        @if ($noteItem->is_published)
                                            <span class="ml-2 inline-flex items-center">
                                                <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                {{ __('Published') }}
                                            </span>
                                        @endif
                                        @if ($noteItem->group)
                                            <span class="ml-2 inline-flex items-center">
                                                <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                {{ $noteItem->group->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white">
            <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data" class="h-full flex flex-col">
                @csrf

                <!-- Note Toolbar -->
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('notes.index') }}" class="text-gray-500 hover:text-gray-700" title="{{ __('Back to Notes') }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div class="flex items-center">
                            <input id="is_pinned" type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} class="rounded border-gray-300 text-yellow-500 shadow-sm focus:ring-yellow-500">
                            <label for="is_pinned" class="ml-2 text-sm text-gray-600">{{ __('Pin') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input id="is_published" type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-500 shadow-sm focus:ring-blue-500">
                            <label for="is_published" class="ml-2 text-sm text-gray-600">{{ __('Publish') }}</label>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ __('New Note') }}
                    </div>
                </div>

                <!-- Note Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus
                            class="w-full text-2xl font-medium text-gray-900 border-0 focus:ring-0 focus:outline-none bg-transparent placeholder-gray-400"
                            placeholder="{{ __('Note title') }}">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Group -->
                    <div>
                        <label for="group_id" class="block text-sm font-medium text-gray-700">{{ __('Group (Optional)') }}</label>
                        <select id="group_id" name="group_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md">
                            <option value="">{{ __('-- Select Group --') }}</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('group_id')" class="mt-2" />
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">{{ __('Image (Optional)') }}</label>
                        <div class="mt-1 flex items-center">
                            <input id="image" type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">{{ __('Content') }}</label>
                        <textarea id="content" name="content" rows="12"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 focus:ring-opacity-50 placeholder-gray-400"
                            placeholder="{{ __('Write your note here...') }}">{{ old('content') }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>
                </div>

                <!-- Note Footer -->
                <div class="border-t border-gray-200 p-4 flex justify-between items-center">
                    <a href="{{ route('notes.index') }}" class="text-gray-500 hover:text-gray-700">
                        {{ __('Cancel') }}
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{ __('Create Note') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
