<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $team->name }}
            </h2>
            <div class="flex items-center gap-4">
                @can('update', $team)
                    <a href="{{ route('teams.edit', $team) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Team') }}
                    </a>
                @endcan
                @can('delete', $team)
                    <form action="{{ route('teams.destroy', $team) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this team?')">
                            {{ __('Delete Team') }}
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

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Team Info -->
                <div class="col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                @if($team->avatar)
                                    <img src="{{ Storage::url($team->avatar) }}" alt="{{ $team->name }}" class="w-20 h-20 rounded-full object-cover">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-gray-500">{{ substr($team->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold">{{ $team->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ __('Created by') }} {{ $team->owner->name }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600">{{ $team->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">{{ __('Team Members') }}</h3>
                                @can('update', $team)
                                    <button type="button" onclick="document.getElementById('add-member-modal').classList.remove('hidden')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Add Member') }}
                                    </button>
                                @endcan
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Member') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($team->members as $member)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $member->name }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($member->id === $team->owner_id)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                            {{ __('Owner') }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $member->pivot->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                            {{ ucfirst($member->pivot->role) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($member->id !== $team->owner_id)
                                                        @can('update', $team)
                                                            <form action="{{ route('teams.members.update-role', [$team, $member]) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                                <select name="role" onchange="this.form.submit()" class="text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                                    <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>{{ __('Member') }}</option>
                                                                    <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                                                </select>
                                                            </form>
                                                        @endcan
                                                        @can('removeMember', $team)
                                                            <form action="{{ route('teams.members.remove', [$team, $member]) }}" method="POST" class="inline ml-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this member?')">
                                                                    {{ __('Remove') }}
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="add-member-modal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('teams.members.add', $team) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ __('Add Team Member') }}
                                </h3>
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="role" :value="__('Role')" />
                                    <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="member">{{ __('Member') }}</option>
                                        <option value="admin">{{ __('Admin') }}</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('role')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Add Member') }}
                        </button>
                        <button type="button" onclick="document.getElementById('add-member-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 