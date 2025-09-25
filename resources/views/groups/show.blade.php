<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Add Note') }}
                </a>
                <a href="{{ route('groups.edit', $group) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit Group') }}
                </a>
                <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to Groups') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ $group->name }}</h1>
                            <div class="flex space-x-2 mb-4">
                                @if ($group->is_published)
                                    <a href="{{ route('groups.published', $group->slug) }}" target="_blank" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ __('Published') }}
                                    </a>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ __('Last updated') }}: {{ $group->updated_at->format('F j, Y, g:i a') }}</p>
                        </div>

                        @if ($group->is_published)
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Public Link') }}:</p>
                                <div class="flex items-center">
                                    <input type="text" value="{{ route('groups.published', $group->slug) }}" class="text-sm text-gray-600 bg-white border border-gray-300 rounded-md px-3 py-2 w-64 mr-2" readonly>
                                    <button onclick="navigator.clipboard.writeText('{{ route('groups.published', $group->slug) }}')" class="inline-flex items-center px-3 py-2 bg-gray-200 border border-transparent rounded-md font-medium text-xs text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Copy') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <form method="POST" action="{{ route('groups.destroy', $group) }}" onsubmit="return confirm('Are you sure you want to delete this group? Notes in this group will remain but will be unassigned.');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>
                                {{ __('Delete Group') }}
                            </x-danger-button>
                        </form>

                        <a href="{{ route('groups.edit', $group) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit Group') }}
                        </a>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-4">{{ __('Notes in this Group') }}</h3>

            @if ($notes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>{{ __('No notes in this group yet.') }}</p>
                        <a href="{{ route('notes.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Create a Note') }}
                        </a>
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
                                        @if ($note->is_published)
                                            <a href="{{ route('notes.published', $note->slug) }}" target="_blank" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Published') }}
                                            </a>
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
                                    <div class="flex space-x-2">
                                        <a href="{{ route('notes.show', $note) }}" class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md font-medium text-xs text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('notes.edit', $note) }}" class="inline-flex items-center px-3 py-1 bg-blue-200 border border-transparent rounded-md font-medium text-xs text-blue-700 hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Edit') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
