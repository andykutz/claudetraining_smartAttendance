<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Users</h2>
            <a href="{{ route('admin.users.create') }}" class="text-sm bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-2 rounded-md hover:from-blue-500 hover:to-indigo-600">New user</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('success'))
                    <div class="p-4 text-sm text-green-700 bg-green-50 border-b border-green-200">{{ session('success') }}</div>
                @endif

                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Location</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-3 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-3 capitalize">{{ $user->role }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $user->location?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-right space-x-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-navy-600 hover:text-navy-800">Edit</a>
                                    @if ($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                            onsubmit="return confirm('Remove {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td class="px-6 py-6 text-gray-500" colspan="5">No users yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
