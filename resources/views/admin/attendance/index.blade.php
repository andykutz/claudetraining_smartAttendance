<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-neutral-900 dark:text-white">Attendance</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <form method="GET" class="card flex flex-wrap items-end gap-4 p-4 sm:p-5">
                @if (Auth::user()->isAdmin())
                    <div class="w-full sm:w-auto">
                        <x-input-label for="location_id" value="Location" />
                        <select id="location_id" name="location_id" class="input mt-1.5 !w-full sm:!w-auto">
                            <option value="">All locations</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected(request('location_id') == $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="w-full sm:w-auto">
                    <x-input-label for="from" value="From" />
                    <input type="date" id="from" name="from" value="{{ request('from') }}" class="input mt-1.5 !w-full sm:!w-auto">
                </div>

                <div class="w-full sm:w-auto">
                    <x-input-label for="to" value="To" />
                    <input type="date" id="to" name="to" value="{{ request('to') }}" class="input mt-1.5 !w-full sm:!w-auto">
                </div>

                <x-primary-button class="w-full sm:w-auto">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Filter
                </x-primary-button>
                <a href="{{ route('admin.attendance.export', request()->query()) }}" class="btn-secondary w-full sm:w-auto">Export CSV</a>
            </form>

            <div class="card">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-head">
                            <tr>
                                <th>Employee</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @forelse ($logs as $log)
                                <tr>
                                    <td>
                                        <span class="font-medium text-neutral-900 dark:text-white">{{ $log->employee->name }}</span>
                                        <span class="text-neutral-400 dark:text-neutral-500">({{ $log->employee->employee_code }})</span>
                                    </td>
                                    <td class="text-neutral-500 dark:text-neutral-400">{{ $log->location->name }}</td>
                                    <td>
                                        @if ($log->type === 'time_in')
                                            <span class="badge badge-success">Time in</span>
                                        @else
                                            <span class="badge badge-danger">Time out</span>
                                        @endif
                                    </td>
                                    <td class="text-neutral-500 dark:text-neutral-400">{{ $log->scanned_at->format('M j, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr class="table-empty"><td colspan="4">No attendance records for this filter.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
