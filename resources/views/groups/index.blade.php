<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Groups') }}
            </h2>
            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create Group') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($groups->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>{{ __('You have no groups yet.') }}</p>
                        <a href="{{ route('groups.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Create Your First Group') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($groups as $group)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold mb-2">{{ $group->name }}</h3>
                                    <div class="flex space-x-2">
                                        @if ($group->is_published)
                                            <a href="{{ route('groups.published', $group->slug) }}" target="_blank" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Published') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-gray-600 mb-4">
                                    {{ $group->notes->count() }} {{ __('notes') }}
                                </p>

                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-sm text-gray-500">{{ $group->updated_at->diffForHumans() }}</span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('groups.show', $group) }}" class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md font-medium text-xs text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('groups.edit', $group) }}" class="inline-flex items-center px-3 py-1 bg-blue-200 border border-transparent rounded-md font-medium text-xs text-blue-700 hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
