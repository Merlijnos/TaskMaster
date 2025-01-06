<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('New Project') }}
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
                    @if($projects->isEmpty())
                        <p class="text-gray-500 text-center">{{ __('No projects found.') }}</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-semibold">
                                            <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-500">
                                                {{ $project->name }}
                                            </a>
                                        </h3>
                                        <span class="px-2 py-1 text-sm rounded-full 
                                            @if($project->priority === 'high') bg-red-100 text-red-800
                                            @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($project->priority) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mt-2 line-clamp-2">{{ $project->description }}</p>
                                    
                                    <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                                        <span>{{ $project->team->name }}</span>
                                        <span>{{ $project->tasks_count ?? 0 }} tasks</span>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-sm @if($project->status === 'completed') text-green-600
                                            @elseif($project->status === 'in_progress') text-blue-600
                                            @else text-gray-600 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Due {{ $project->due_date->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 