<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Attendance</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex flex-wrap gap-4 items-end">
                @if (Auth::user()->isAdmin())
                    <div>
                        <x-input-label for="location_id" value="Location" />
                        <select id="location_id" name="location_id" class="mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">All locations</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected(request('location_id') == $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <x-input-label for="from" value="From" />
                    <input type="date" id="from" name="from" value="{{ request('from') }}" class="mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                </div>

                <div>
                    <x-input-label for="to" value="To" />
                    <input type="date" id="to" name="to" value="{{ request('to') }}" class="mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                </div>

                <x-primary-button>Filter</x-primary-button>
                <a href="{{ route('admin.attendance.export', request()->query()) }}" class="text-sm bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">Export CSV</a>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Employee</th>
                            <th class="px-6 py-3">Location</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-6 py-3">{{ $log->employee->name }} ({{ $log->employee->employee_code }})</td>
                                <td class="px-6 py-3 text-gray-500">{{ $log->location->name }}</td>
                                <td class="px-6 py-3">
                                    @if ($log->type === 'time_in')
                                        <span class="text-green-700">Time in</span>
                                    @else
                                        <span class="text-red-700">Time out</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $log->scanned_at->format('M j, Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td class="px-6 py-6 text-gray-500" colspan="4">No attendance records for this filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
