<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latest('scanned_at')
            ->paginate(30)
            ->withQueryString();

        $locations = $this->availableLocations($request);

        return view('admin.attendance.index', compact('logs', 'locations'));
    }

    public function export(Request $request): StreamedResponse
    {
        $logs = $this->filteredQuery($request)->orderBy('scanned_at')->get();

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee code', 'Employee name', 'Location', 'Type', 'Scanned at']);

            foreach ($logs as $log) {
                fputcsv($out, [
                    $log->employee->employee_code,
                    $log->employee->name,
                    $log->location->name,
                    $log->type,
                    $log->scanned_at->toDateTimeString(),
                ]);
            }

            fclose($out);
        }, 'attendance.csv');
    }

    private function filteredQuery(Request $request)
    {
        $user = $request->user();

        $query = AttendanceLog::query()->with(['employee', 'location']);

        if (! $user->isAdmin()) {
            $query->where('location_id', $user->location_id);
        } elseif ($request->filled('location_id')) {
            $query->where('location_id', $request->integer('location_id'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('scanned_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('scanned_at', '<=', $request->date('to'));
        }

        return $query;
    }

    private function availableLocations(Request $request)
    {
        $user = $request->user();

        return $user->isAdmin()
            ? Location::orderBy('name')->get()
            : Location::where('id', $user->location_id)->get();
    }
}
