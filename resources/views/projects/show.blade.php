<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            <div class="flex items-center gap-4">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Project') }}
                    </a>
                @endcan
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this project?')">
                            {{ __('Delete Project') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Project Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">{{ __('Project Details') }}</h3>
                                    <p class="text-gray-600">{{ $project->description }}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="px-2 py-1 text-sm rounded-full mb-2
                                        @if($project->priority === 'high') bg-red-100 text-red-800
                                        @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($project->priority) }} Priority
                                    </span>
                                    <span class="text-sm @if($project->status === 'completed') text-green-600
                                        @elseif($project->status === 'in_progress') text-blue-600
                                        @else text-gray-600 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                                <div>
                                    <p class="text-gray-600">{{ __('Team') }}</p>
                                    <p class="font-medium">{{ $project->team->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ __('Created By') }}</p>
                                    <p class="font-medium">{{ $project->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ __('Start Date') }}</p>
                                    <p class="font-medium">{{ $project->start_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ __('Due Date') }}</p>
                                    <p class="font-medium">{{ $project->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks List -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">{{ __('Tasks') }}</h3>
                                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                                    {{ __('Add Task') }}
                                </a>
                            </div>

                            @if($project->tasks->isEmpty())
                                <p class="text-gray-500 text-center py-4">{{ __('No tasks found.') }}</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($project->tasks as $task)
                                        <div class="border rounded-lg p-4 hover:shadow-sm transition-shadow">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium">
                                                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-500">
                                                            {{ $task->title }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                                </div>
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($task->priority === 'high') bg-red-100 text-red-800
                                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center mt-4 text-sm">
                                                <span class="text-gray-600">
                                                    {{ __('Assigned to:') }} {{ $task->assignedUser->name }}
                                                </span>
                                                <span class="text-gray-600">
                                                    {{ __('Due:') }} {{ $task->due_date->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Comments') }}</h3>

                            <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                                @csrf
                                <input type="hidden" name="commentable_type" value="App\Models\Project">
                                <input type="hidden" name="commentable_id" value="{{ $project->id }}">
                                
                                <div>
                                    <textarea name="content" rows="3" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="{{ __('Add a comment...') }}" required></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('content')" />
                                </div>

                                <div class="mt-2">
                                    <x-primary-button>{{ __('Post Comment') }}</x-primary-button>
                                </div>
                            </form>

                            <div class="space-y-4">
                                @forelse($project->comments as $comment)
                                    <div class="border-b pb-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="font-medium">{{ $comment->user->name }}</span>
                                                <span class="text-gray-500 text-sm ml-2">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            @if(auth()->id() === $comment->user_id)
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 mt-2">{{ $comment->content }}</p>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center">{{ __('No comments yet.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 