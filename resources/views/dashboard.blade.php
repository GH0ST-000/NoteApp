<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Notes Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">{{ __('My Notes') }}</h3>
                            <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                {{ $notesCount }} {{ __('total') }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4">{{ __('Manage your personal notes, organize them into groups, and share them with others.') }}</p>
                        <div class="flex space-x-2">
                            <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('View All Notes') }}
                            </a>
                            <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Note') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Groups Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">{{ __('My Groups') }}</h3>
                            <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                {{ $groupsCount }} {{ __('total') }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4">{{ __('Organize your notes into groups for better management and sharing.') }}</p>
                        <div class="flex space-x-2">
                            <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('View All Groups') }}
                            </a>
                            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Group') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Notes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Recent Notes') }}</h3>

                    @if ($recentNotes->isEmpty())
                        <p class="text-gray-600">{{ __('You have no notes yet.') }}</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($recentNotes as $note)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium">{{ $note->title }}</h4>
                                            <p class="text-sm text-gray-600 line-clamp-1">{{ Str::limit($note->content, 100) }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if ($note->is_pinned)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    {{ __('Pinned') }}
                                                </span>
                                            @endif
                                            @if ($note->is_published)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ __('Published') }}
                                                </span>
                                            @endif
                                            @if ($note->group)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $note->group->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-xs text-gray-500">{{ $note->updated_at->diffForHumans() }}</span>
                                        <a href="{{ route('notes.show', $note) }}" class="text-sm text-blue-600 hover:text-blue-800">{{ __('View') }} →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
