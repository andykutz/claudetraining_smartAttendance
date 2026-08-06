<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Attendance Reports</h2>
            <a href="{{ route('admin.attendance.index') }}" class="text-sm text-blue-200 hover:text-white">← Back to Attendance</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @php
                $fortnightEnd = $start->copy()->addDays(13);
            @endphp

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
                    <x-input-label for="start" value="Fortnight starting" />
                    <input type="date" id="start" name="start" value="{{ $start->format('Y-m-d') }}" class="mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                </div>

                <x-primary-button>Generate</x-primary-button>

                <div class="ms-auto flex gap-2">
                    <a href="{{ route('admin.attendance.reports.download', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center gap-2 text-sm bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2 rounded-lg shadow-sm hover:from-emerald-500 hover:to-emerald-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Excel
                    </a>
                    <a href="{{ route('admin.attendance.reports.download', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="inline-flex items-center gap-2 text-sm bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-2 rounded-lg shadow-sm hover:from-red-400 hover:to-rose-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        PDF
                    </a>
                </div>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Fortnightly hours report</h3>
                        <p class="text-xs text-gray-500">{{ $start->format('D, M j, Y') }} – {{ $fortnightEnd->format('D, M j, Y') }} · {{ count($workingDays) }} working days (Mon–Fri)</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ count($rows) }} employee(s)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="bg-navy-900 text-white uppercase text-xs">
                                <th class="px-4 py-3" rowspan="2">Employee</th>
                                <th class="px-4 py-3" rowspan="2">Code</th>
                                <th class="px-4 py-3 text-center border-l border-navy-800" colspan="5">Week 1</th>
                                <th class="px-4 py-3 text-center border-l border-navy-800" colspan="5">Week 2</th>
                                <th class="px-4 py-3 text-center border-l border-navy-800" rowspan="2">Fortnight Hours</th>
                                <th class="px-4 py-3 text-center border-l border-navy-800" rowspan="2">Days Worked ({{ count($workingDays) }})</th>
                            </tr>
                            <tr class="bg-navy-800 text-white uppercase text-xs">
                                @foreach ($workingDays as $day)
                                    <th class="px-3 py-2 text-center border-l border-navy-900">{{ $day->format('D') }}<span class="block text-[10px] text-blue-200 font-normal">{{ $day->format('m/d') }}</span></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($rows as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['employee']->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['employee']->employee_code }}</td>
                                    @foreach ($row['days'] as $day)
                                        <td class="px-3 py-3 text-center text-gray-600 {{ $day['present'] ? '' : 'text-gray-300' }}">
                                            {{ $day['present'] ? number_format($day['hours'], 2).'h' : '—' }}
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center font-semibold text-navy-900 border-l border-gray-100">{{ number_format($row['total_hours'], 2) }}h</td>
                                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">{{ $row['days_worked'] }} / {{ count($workingDays) }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 text-gray-500" colspan="14">No employees found for this period.</td></tr>
                            @endforelse
                        </tbody>
                        @if (count($rows))
                            <tfoot class="bg-gray-50 font-semibold">
                                <tr>
                                    <td class="px-4 py-3 text-gray-800" colspan="2">Totals</td>
                                    @foreach ($totals as $total)
                                        <td class="px-3 py-3 text-center text-gray-800">{{ number_format($total, 2) }}h</td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center text-navy-900 border-l border-gray-200">{{ number_format(array_sum($totals), 2) }}h</td>
                                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-200">—</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
