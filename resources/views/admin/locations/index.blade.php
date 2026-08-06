<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Locations</h2>
            <a href="{{ route('admin.locations.create') }}" class="text-sm bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-2 rounded-md hover:from-blue-500 hover:to-indigo-600">New location</a>
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
                            <th class="px-6 py-3">Address</th>
                            <th class="px-6 py-3">Employees</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($locations as $location)
                            <tr>
                                <td class="px-6 py-3 font-medium">{{ $location->name }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $location->address }}</td>
                                <td class="px-6 py-3">{{ $location->employees_count }}</td>
                                <td class="px-6 py-3">
                                    @if ($location->active)
                                        <span class="text-green-700">Active</span>
                                    @else
                                        <span class="text-gray-400">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right space-x-3">
                                    <a href="{{ route('admin.locations.show', $location) }}" class="text-navy-600 hover:text-navy-800">QR code</a>
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="text-navy-600 hover:text-navy-800">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="px-6 py-6 text-gray-500" colspan="5">No locations yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $locations->links() }}</div>
        </div>
    </div>
</x-app-layout>
