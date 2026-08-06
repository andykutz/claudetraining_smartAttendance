<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private const WORKING_DAYS = 10;

    public function index(Request $request)
    {
        $start = $this->resolveFortnightStart($request);
        $report = $this->buildRows($request, $start);

        return view('admin.attendance.reports', [
            'start' => $start,
            'workingDays' => $report['workingDays'],
            'rows' => $report['rows'],
            'totals' => $report['totals'],
            'locations' => $this->availableLocations($request),
        ]);
    }

    public function download(Request $request): Response
    {
        $format = $request->query('format', 'excel');
        $start = $this->resolveFortnightStart($request);
        $report = $this->buildRows($request, $start);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.attendance.reports-pdf', [
                'start' => $start,
                'workingDays' => $report['workingDays'],
                'rows' => $report['rows'],
                'totals' => $report['totals'],
            ]);

            return $pdf->download('attendance-report-'.$start->format('Y-m-d').'.pdf');
        }

        return $this->excelDownload($start, $report['workingDays'], $report['rows'], $report['totals']);
    }

    private function resolveFortnightStart(Request $request): Carbon
    {
        $start = $request->filled('start')
            ? Carbon::parse($request->query('start'))->startOfDay()
            : today();

        return $start->copy()->startOfWeek(Carbon::MONDAY);
    }

    private function workingDays(Carbon $start): array
    {
        return collect(range(0, 13))
            ->map(fn (int $i) => $start->copy()->addDays($i))
            ->filter(fn (Carbon $day) => $day->isWeekday())
            ->values()
            ->all();
    }

    private function buildRows(Request $request, Carbon $start): array
    {
        $end = $start->copy()->addDays(13)->endOfDay();
        $workingDays = $this->workingDays($start);

        $employees = Employee::query()->with('homeLocation')->where('active', true);
        $logs = AttendanceLog::query()->whereBetween('scanned_at', [$start->copy()->startOfDay(), $end]);

        $user = $request->user();

        if (! $user->isAdmin()) {
            $employees->where('home_location_id', $user->location_id);
            $logs->where('location_id', $user->location_id);
        } elseif ($request->filled('location_id')) {
            $employees->where('home_location_id', $request->integer('location_id'));
            $logs->where('location_id', $request->integer('location_id'));
        }

        $employees = $employees->orderBy('name')->get();
        $logs = $logs->orderBy('scanned_at')->get()->groupBy('employee_id');

        $rows = [];
        $totals = array_fill(0, count($workingDays), 0.0);

        foreach ($employees as $employee) {
            $row = [
                'employee' => $employee,
                'days' => [],
                'total_hours' => 0.0,
                'days_worked' => 0,
            ];

            foreach ($workingDays as $i => $day) {
                $dayLogs = $logs->get($employee->id, collect())
                    ->filter(fn (AttendanceLog $log) => $log->scanned_at->toDateString() === $day->toDateString())
                    ->values();

                $timeIn = $dayLogs->firstWhere('type', 'time_in');
                $timeOut = $dayLogs->where('type', 'time_out')->last();

                $hours = 0.0;
                if ($timeIn && $timeOut && $timeOut->scanned_at->gt($timeIn->scanned_at)) {
                    $hours = round($timeIn->scanned_at->diffInMinutes($timeOut->scanned_at) / 60, 2);
                }

                $row['days'][$i] = [
                    'date' => $day,
                    'hours' => $hours,
                    'present' => $timeIn !== null,
                ];
                $row['total_hours'] = round($row['total_hours'] + $hours, 2);
                $totals[$i] = round($totals[$i] + $hours, 2);

                if ($timeIn !== null) {
                    $row['days_worked']++;
                }
            }

            $rows[] = $row;
        }

        return [
            'workingDays' => $workingDays,
            'rows' => $rows,
            'totals' => $totals,
        ];
    }

    private function availableLocations(Request $request)
    {
        $user = $request->user();

        return $user->isAdmin()
            ? Location::orderBy('name')->get()
            : Location::where('id', $user->location_id)->get();
    }

    private function excelDownload(Carbon $start, array $workingDays, array $rows, array $totals): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Attendance Report');

        $end = $start->copy()->addDays(13);

        $sheet->setCellValue('A1', 'Attendance Report - '.$start->format('F d, Y').' to '.$end->format('F d, Y'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $headers = ['Employee', 'Code'];
        foreach ($workingDays as $i => $day) {
            $week = $i < 5 ? 1 : 2;
            $headers[] = 'W'.$week.' '.$weekdays[$i % 5].' '.$day->format('m/d');
        }
        $headers[] = 'Fortnight Hours';
        $headers[] = 'Days Worked ('.self::WORKING_DAYS.')';

        $headerRow = 3;
        foreach ($headers as $col => $header) {
            $coord = Coordinate::stringFromColumnIndex($col + 1).$headerRow;
            $sheet->setCellValue($coord, $header);
            $sheet->getStyle($coord)->getFont()->setBold(true);
            $sheet->getStyle($coord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E2B47');
            $sheet->getStyle($coord)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($coord)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $rowIndex = $headerRow + 1;
        foreach ($rows as $row) {
            $sheet->setCellValue('A'.$rowIndex, $row['employee']->name);
            $sheet->setCellValue('B'.$rowIndex, $row['employee']->employee_code);

            foreach ($row['days'] as $i => $day) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 3).$rowIndex, $day['present'] ? $day['hours'] : '');
            }

            $sheet->setCellValue(Coordinate::stringFromColumnIndex(count($headers) - 1).$rowIndex, $row['total_hours']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex(count($headers)).$rowIndex, $row['days_worked'].' / '.self::WORKING_DAYS);

            $rowIndex++;
        }

        $sheet->setCellValue('A'.$rowIndex, 'Totals');
        $sheet->getStyle('A'.$rowIndex)->getFont()->setBold(true);

        foreach ($totals as $i => $total) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 3).$rowIndex, $total);
        }
        $totalsCoord = Coordinate::stringFromColumnIndex(count($headers) - 1).$rowIndex;
        $sheet->setCellValue($totalsCoord, round(array_sum($totals), 2));
        $sheet->getStyle($totalsCoord)->getFont()->setBold(true);

        $sheet->getColumnDimension('A')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(12);
        for ($i = 3; $i <= count($headers); $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setWidth(12);
        }

        $fileName = 'attendance-report-'.$start->format('Y-m-d').'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
