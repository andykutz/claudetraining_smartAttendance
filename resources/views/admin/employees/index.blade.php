<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Employees</h2>
            <a href="{{ route('admin.employees.create') }}" class="text-sm bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-2 rounded-md hover:from-blue-500 hover:to-indigo-600">Add employee</a>
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
                            <th class="px-6 py-3">Code</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Location</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="px-6 py-3 font-medium">{{ $employee->employee_code }}</td>
                                <td class="px-6 py-3">{{ $employee->name }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $employee->homeLocation?->name }}</td>
                                <td class="px-6 py-3">
                                    @if ($employee->active)
                                        <span class="text-green-700">Active</span>
                                    @else
                                        <span class="text-gray-400">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right space-x-3">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="text-navy-600 hover:text-navy-800">Edit</a>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline"
                                        onsubmit="return confirm('Remove {{ $employee->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="px-6 py-6 text-gray-500" colspan="5">No employees yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $employees->links() }}</div>
        </div>
    </div>
</x-app-layout>
