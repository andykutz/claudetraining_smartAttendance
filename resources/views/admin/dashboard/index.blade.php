<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Dashboard</h2>
            <span class="text-sm text-blue-200">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </x-slot>

    @php
        $chartData = [
            'labels' => array_column($trend, 'label'),
            'ins' => array_column($trend, 'in'),
            'outs' => array_column($trend, 'out'),
            'totals' => array_column($trend, 'total'),
            'statusLabels' => ['On the clock', 'Checked out', 'Absent'],
            'statusData' => [$status['on_clock'], $status['checked_out'], $status['absent']],
            'locationLabels' => array_column($locations, 'name'),
            'locationData' => array_column($locations, 'present'),
        ];
        $maxPresent = max(array_column($locations, 'present') ?: [0]) ?: 1;
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- KPI cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <button type="button" @click="$dispatch('open-modal', 'card-checked-in')" class="group bg-white shadow-sm sm:rounded-xl p-5 flex items-center gap-4 text-left cursor-pointer hover:ring-2 hover:ring-blue-200 hover:shadow-lg transition-all duration-150">
                    <span class="flex items-center justify-center h-11 w-11 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <div class="flex-1">
                        <div class="text-2xl font-bold text-navy-900">{{ $checkedInToday }}</div>
                        <div class="text-xs text-gray-500">Checked in today</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>

                <button type="button" @click="$dispatch('open-modal', 'card-on-clock')" class="group bg-white shadow-sm sm:rounded-xl p-5 flex items-center gap-4 text-left cursor-pointer hover:ring-2 hover:ring-blue-200 hover:shadow-lg transition-all duration-150">
                    <span class="flex items-center justify-center h-11 w-11 rounded-lg bg-gradient-to-br from-navy-600 to-navy-800 shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <div class="flex-1">
                        <div class="text-2xl font-bold text-navy-900">{{ $onClockNow }}</div>
                        <div class="text-xs text-gray-500">On the clock now</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>

                <button type="button" @click="$dispatch('open-modal', 'card-rate')" class="group bg-white shadow-sm sm:rounded-xl p-5 flex items-center gap-4 text-left cursor-pointer hover:ring-2 hover:ring-blue-200 hover:shadow-lg transition-all duration-150">
                    <span class="flex items-center justify-center h-11 w-11 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </span>
                    <div class="flex-1">
                        <div class="text-2xl font-bold text-navy-900">{{ $attendanceRate }}%</div>
                        <div class="text-xs text-gray-500">Attendance rate</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>

                <button type="button" @click="$dispatch('open-modal', 'card-employees')" class="group bg-white shadow-sm sm:rounded-xl p-5 flex items-center gap-4 text-left cursor-pointer hover:ring-2 hover:ring-blue-200 hover:shadow-lg transition-all duration-150">
                    <span class="flex items-center justify-center h-11 w-11 rounded-lg bg-gradient-to-br from-navy-500 to-navy-700 shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                    <div class="flex-1">
                        <div class="text-2xl font-bold text-navy-900">{{ $activeEmployees }}</div>
                        <div class="text-xs text-gray-500">Active employees</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>

                <button type="button" @click="$dispatch('open-modal', 'card-locations')" class="group bg-white shadow-sm sm:rounded-xl p-5 flex items-center gap-4 text-left cursor-pointer hover:ring-2 hover:ring-blue-200 hover:shadow-lg transition-all duration-150">
                    <span class="flex items-center justify-center h-11 w-11 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </span>
                    <div class="flex-1">
                        <div class="text-2xl font-bold text-navy-900">{{ $totalLocations }}</div>
                        <div class="text-xs text-gray-500">Locations</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>

                <button type="button" @click="$dispatch('open-modal', 'card-logs-today')" class="group bg-white shadow-sm sm:rounded-xl p-5 flex items-center gap-4 text-left cursor-pointer hover:ring-2 hover:ring-blue-200 hover:shadow-lg transition-all duration-150">
                    <span class="flex items-center justify-center h-11 w-11 rounded-lg bg-gradient-to-br from-navy-500 to-blue-700 shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </span>
                    <div class="flex-1">
                        <div class="text-2xl font-bold text-navy-900">{{ $logsToday }}</div>
                        <div class="text-xs text-gray-500">Logs today</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>

            {{-- Charts row 1: trend bar + status doughnut --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-slate-800">Time in / Time out</h3>
                        <span class="text-xs text-gray-500">Last 7 days</span>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">Employee status today</h3>
                    <div class="h-64 relative">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Charts row 2: activity line + location doughnut --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-slate-800">Attendance activity</h3>
                        <span class="text-xs text-gray-500">Last 7 days</span>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">Present today by location</h3>
                    @forelse ($locations as $location)
                        <div class="mb-3 last:mb-0">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700 font-medium">{{ $location['name'] }}</span>
                                <span class="text-gray-500">{{ $location['present'] }}</span>
                            </div>
                            <div class="mt-1.5 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300"
                                     style="width: {{ round($location['present'] / $maxPresent * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No locations yet.</p>
                    @endforelse
                    <div class="mt-4 h-48 relative">
                        <canvas id="locationChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Recent activity --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-800">Recent activity</h3>
                    <a href="{{ route('admin.attendance.index') }}" class="text-sm text-navy-600 hover:text-navy-800">View all</a>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Employee</th>
                            <th class="px-5 py-3">Location</th>
                            <th class="px-5 py-3">Type</th>
                            <th class="px-5 py-3">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentLogs as $log)
                            <tr>
                                <td class="px-5 py-3">{{ $log->employee->name }} <span class="text-gray-400">({{ $log->employee->employee_code }})</span></td>
                                <td class="px-5 py-3 text-gray-500">{{ $log->location->name }}</td>
                                <td class="px-5 py-3">
                                    @if ($log->type === 'time_in')
                                        <span class="inline-flex items-center gap-1 text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600 inline-block"></span>Time in</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-red-700"><span class="h-1.5 w-1.5 rounded-full bg-red-600 inline-block"></span>Time out</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $log->scanned_at->format('M j, Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td class="px-5 py-6 text-gray-500" colspan="4">No attendance activity yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Card detail modals --}}
    <x-modal name="card-checked-in" :show="false" maxWidth="2xl" focusable>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Checked in today ({{ $checkedInToday }})</h3>
            <button @click="$dispatch('close-modal', 'card-checked-in')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @forelse ($checkedInEmployees as $emp)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $emp['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $emp['code'] }}</div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $emp['time'] }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500">Nobody has checked in yet today.</li>
                @endforelse
            </ul>
        </div>
    </x-modal>

    <x-modal name="card-on-clock" :show="false" maxWidth="2xl" focusable>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">On the clock now ({{ $onClockNow }})</h3>
            <button @click="$dispatch('close-modal', 'card-on-clock')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @forelse ($onClockEmployees as $emp)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $emp['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $emp['code'] }}</div>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600 inline-block"></span>{{ $emp['time'] }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500">Nobody is currently on the clock.</li>
                @endforelse
            </ul>
        </div>
    </x-modal>

    <x-modal name="card-rate" :show="false" maxWidth="2xl" focusable>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Attendance rate ({{ $attendanceRate }}%)</h3>
            <button @click="$dispatch('close-modal', 'card-rate')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center bg-gray-50 rounded-lg py-3">
                    <div class="text-2xl font-bold text-navy-900">{{ $status['on_clock'] }}</div>
                    <div class="text-xs text-gray-500">On the clock</div>
                </div>
                <div class="text-center bg-gray-50 rounded-lg py-3">
                    <div class="text-2xl font-bold text-navy-900">{{ $status['checked_out'] }}</div>
                    <div class="text-xs text-gray-500">Checked out</div>
                </div>
                <div class="text-center bg-gray-50 rounded-lg py-3">
                    <div class="text-2xl font-bold text-navy-900">{{ $status['absent'] }}</div>
                    <div class="text-xs text-gray-500">Absent today</div>
                </div>
            </div>

            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">On the clock</h4>
            <ul class="divide-y divide-gray-100 mb-4">
                @forelse ($onClockEmployees as $emp)
                    <li class="py-2 flex items-center justify-between">
                        <span class="text-sm text-gray-800">{{ $emp['name'] }}</span>
                        <span class="text-xs text-gray-500">{{ $emp['time'] }}</span>
                    </li>
                @empty
                    <li class="py-2 text-sm text-gray-500">None</li>
                @endforelse
            </ul>

            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Checked out</h4>
            <ul class="divide-y divide-gray-100 mb-4">
                @forelse ($checkedOutEmployees as $emp)
                    <li class="py-2 flex items-center justify-between">
                        <span class="text-sm text-gray-800">{{ $emp['name'] }}</span>
                        <span class="text-xs text-gray-500">{{ $emp['time'] }}</span>
                    </li>
                @empty
                    <li class="py-2 text-sm text-gray-500">None</li>
                @endforelse
            </ul>

            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Absent today</h4>
            <ul class="divide-y divide-gray-100">
                @forelse ($absentEmployees as $emp)
                    <li class="py-2 flex items-center justify-between">
                        <span class="text-sm text-gray-800">{{ $emp['name'] }}</span>
                        <span class="text-xs text-gray-500">{{ $emp['code'] }}</span>
                    </li>
                @empty
                    <li class="py-2 text-sm text-gray-500">Everyone has logged in today.</li>
                @endforelse
            </ul>
        </div>
    </x-modal>

    <x-modal name="card-employees" :show="false" maxWidth="2xl" focusable>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Active employees ({{ $activeEmployees }})</h3>
            <button @click="$dispatch('close-modal', 'card-employees')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @forelse ($activeEmployeeList as $emp)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $emp['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $emp['code'] }}</div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $emp['location'] ?? 'No location' }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500">No active employees yet.</li>
                @endforelse
            </ul>
        </div>
    </x-modal>

    <x-modal name="card-locations" :show="false" maxWidth="2xl" focusable>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Locations ({{ $totalLocations }})</h3>
            <button @click="$dispatch('close-modal', 'card-locations')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @forelse ($locations as $location)
                    <li class="py-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-800 font-medium">{{ $location['name'] }}</span>
                            <span class="text-gray-500">{{ $location['present'] }} present</span>
                        </div>
                        <div class="mt-1.5 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ round($location['present'] / $maxPresent * 100) }}%"></div>
                        </div>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500">No locations yet.</li>
                @endforelse
            </ul>
        </div>
    </x-modal>

    <x-modal name="card-logs-today" :show="false" maxWidth="2xl" focusable>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Logs today ({{ $logsToday }})</h3>
            <button @click="$dispatch('close-modal', 'card-logs-today')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @forelse ($todayLogs as $log)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $log['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $log['code'] }}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if ($log['type'] === 'time_in')
                                <span class="text-xs text-green-700">Time in</span>
                            @else
                                <span class="text-xs text-red-700">Time out</span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $log['time'] }}</span>
                        </div>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500">No attendance logs yet today.</li>
                @endforelse
            </ul>
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const data = @json($chartData);

            const themeColors = {
                light: { default: '#64748b', grid: 'rgba(148, 163, 184, 0.25)', card: '#ffffff', absent: '#e5e7eb' },
                dark: { default: '#93a4c2', grid: 'rgba(148, 163, 184, 0.12)', card: '#16233c', absent: '#334155' },
            };

            function buildCharts() {
                const isDark = document.documentElement.classList.contains('dark');
                const t = themeColors[isDark ? 'dark' : 'light'];

                Chart.defaults.color = t.default;
                Chart.defaults.font.family = 'Figtree, sans-serif';

                new Chart(document.getElementById('trendChart'), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [
                            { label: 'Time in', data: data.ins, backgroundColor: '#2563eb', hoverBackgroundColor: '#1d4ed8', borderRadius: 5, maxBarThickness: 34 },
                            { label: 'Time out', data: data.outs, backgroundColor: '#6366f1', hoverBackgroundColor: '#4f46e5', borderRadius: 5, maxBarThickness: 34 },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, grid: { color: t.grid }, ticks: { precision: 0 } },
                        },
                    },
                });

                new Chart(document.getElementById('activityChart'), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Logs',
                            data: data.totals,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.18)',
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: isDark ? '#16233c' : '#ffffff',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, grid: { color: t.grid }, ticks: { precision: 0 } },
                        },
                    },
                });

                new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: data.statusLabels,
                        datasets: [{
                            data: data.statusData,
                            backgroundColor: ['#2563eb', '#8b5cf6', t.absent],
                            hoverBackgroundColor: ['#1d4ed8', '#7c3aed', t.absent],
                            borderWidth: 3,
                            borderColor: t.card,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } },
                    },
                });

                new Chart(document.getElementById('locationChart'), {
                    type: 'doughnut',
                    data: {
                        labels: data.locationLabels,
                        datasets: [{
                            data: data.locationData,
                            backgroundColor: ['#3b82f6', '#6366f1', '#06b6d4', '#8b5cf6', '#14b8a6', '#64748b'],
                            hoverBackgroundColor: ['#2563eb', '#4f46e5', '#0891b2', '#7c3aed', '#0d9488', '#475569'],
                            borderWidth: 3,
                            borderColor: t.card,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } },
                    },
                });
            }

            function destroyCharts() {
                Object.values(Chart.instances).forEach(function (chart) { chart.destroy(); });
            }

            buildCharts();
            document.addEventListener('themechange', function () {
                destroyCharts();
                buildCharts();
            });
        });
    </script>
</x-app-layout>
