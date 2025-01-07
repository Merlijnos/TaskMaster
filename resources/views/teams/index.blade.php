<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Teams') }}
            </h2>
            <a href="{{ route('teams.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('New Team') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($teams->isEmpty())
                        <p class="text-gray-500 text-center">{{ __('No teams found.') }}</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($teams as $team)
                                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center">
                                            @if($team->avatar)
                                                <img src="{{ Storage::url($team->avatar) }}" alt="{{ $team->name }}" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-xl font-bold text-gray-500">{{ substr($team->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold">
                                                    <a href="{{ route('teams.show', $team) }}" class="hover:text-blue-500">
                                                        {{ $team->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ __('Created by') }} {{ $team->owner->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mt-4 text-gray-600 line-clamp-2">{{ $team->description }}</p>

                                    <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                                        <span>{{ $team->members_count }} {{ Str::plural('member', $team->members_count) }}</span>
                                        <span>{{ $team->projects_count }} {{ Str::plural('project', $team->projects_count) }}</span>
                                    </div>

                                    @if($team->pivot)
                                        <div class="mt-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($team->pivot->role === 'admin') bg-purple-100 text-purple-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($team->pivot->role) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $teams->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 