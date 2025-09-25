<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Group') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('groups.show', $group) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('View Group') }}
                </a>
                <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to Groups') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('groups.update', $group) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $group->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Options -->
                        <div class="mb-4 flex space-x-6">
                            <div class="flex items-center">
                                <input id="is_published" type="checkbox" name="is_published" value="1" {{ old('is_published', $group->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="is_published" class="ml-2 text-sm text-gray-600">{{ __('Publish this group') }}</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this group? Notes in this group will remain but will be unassigned.');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button>
                                    {{ __('Delete Group') }}
                                </x-danger-button>
                            </form>

                            <x-primary-button>
                                {{ __('Update Group') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
