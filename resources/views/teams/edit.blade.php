<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Team:') }} {{ $team->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('teams.update', $team) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Team Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $team->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $team->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="avatar" :value="__('Team Avatar')" />
                            @if($team->avatar)
                                <div class="mt-2 mb-4">
                                    <span class="text-sm text-gray-500">Current Avatar:</span>
                                    <img src="{{ Storage::url($team->avatar) }}" alt="{{ $team->name }}" class="mt-1 w-20 h-20 rounded-full object-cover">
                                </div>
                            @endif
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100" />
                            <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 1MB</p>
                            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Team') }}</x-primary-button>
                            <a href="{{ route('teams.show', $team) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 