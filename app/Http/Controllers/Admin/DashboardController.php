<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $locationScope = $user->isAdmin() ? null : $user->location_id;

        $employeesQuery = Employee::query();
        $logsQuery = AttendanceLog::query();

        if ($locationScope) {
            $employeesQuery->where('home_location_id', $locationScope);
            $logsQuery->where('location_id', $locationScope);
        }

        $activeEmployees = (clone $employeesQuery)->where('active', true)->get();
        $employeeIds = $activeEmployees->pluck('id');

        $latestLogsToday = AttendanceLog::query()
            ->whereDate('scanned_at', today())
            ->whereIn('employee_id', $employeeIds);
        if ($locationScope) {
            $latestLogsToday->where('location_id', $locationScope);
        }

        $latestPerEmployee = $latestLogsToday->get()
            ->groupBy('employee_id')
            ->map(fn ($group) => $group->sortByDesc('scanned_at')->first());

        $onClockNow = $latestPerEmployee->filter(fn ($log) => $log->type === 'time_in')->count();
        $checkedOut = $latestPerEmployee->filter(fn ($log) => $log->type === 'time_out')->count();
        $absent = max(0, $activeEmployees->count() - $onClockNow - $checkedOut);

        $checkedInToday = (clone $logsQuery)
            ->whereDate('scanned_at', today())
            ->where('type', 'time_in')
            ->distinct()
            ->count('employee_id');

        $activeCount = $activeEmployees->count();
        $attendanceRate = $activeCount > 0 ? round($checkedInToday / $activeCount * 100) : 0;

        return view('admin.dashboard.index', [
            'totalEmployees' => (clone $employeesQuery)->count(),
            'activeEmployees' => $activeCount,
            'totalLocations' => $user->isAdmin() ? Location::where('active', true)->count() : 1,
            'logsToday' => (clone $logsQuery)->whereDate('scanned_at', today())->count(),
            'logsThisMonth' => (clone $logsQuery)->whereMonth('scanned_at', now()->month)->whereYear('scanned_at', now()->year)->count(),
            'checkedInToday' => $checkedInToday,
            'onClockNow' => $onClockNow,
            'attendanceRate' => $attendanceRate,
            'status' => [
                'on_clock' => $onClockNow,
                'checked_out' => $checkedOut,
                'absent' => $absent,
            ],
            'trend' => $this->weeklyTrend($logsQuery),
            'locations' => $this->locationStats($logsQuery, $locationScope),
            'recentLogs' => (clone $logsQuery)
                ->with(['employee', 'location'])
                ->latest('scanned_at')
                ->limit(10)
                ->get(),
        ]);
    }

    private function weeklyTrend($logsQuery): array
    {
        $start = today()->subDays(6);

        $grouped = (clone $logsQuery)
            ->whereDate('scanned_at', '>=', $start)
            ->get()
            ->groupBy(fn (AttendanceLog $log) => $log->scanned_at->toDateString());

        return collect(range(6, 0))->map(function (int $daysAgo) use ($grouped) {
            $day = today()->subDays($daysAgo);
            $dayLogs = $grouped->get($day->toDateString(), collect());

            return [
                'label' => $day->format('D'),
                'in' => $dayLogs->where('type', 'time_in')->count(),
                'out' => $dayLogs->where('type', 'time_out')->count(),
                'total' => $dayLogs->count(),
            ];
        })->all();
    }

    private function locationStats($logsQuery, ?int $locationScope): array
    {
        $locations = $locationScope
            ? Location::where('id', $locationScope)->get(['id', 'name'])
            : Location::orderBy('name')->get(['id', 'name']);

        $present = (clone $logsQuery)
            ->whereDate('scanned_at', today())
            ->where('type', 'time_in')
            ->get()
            ->groupBy('location_id')
            ->map(fn ($group) => $group->pluck('employee_id')->unique()->count());

        return $locations->map(fn (Location $location) => [
            'name' => $location->name,
            'present' => $present->get($location->id, 0),
        ])->all();
    }
}
