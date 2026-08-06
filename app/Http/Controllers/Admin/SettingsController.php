<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', ['groups' => $this->connectionPoints()]);
    }

    public function apiDocsPdf()
    {
        $pdf = Pdf::loadView('admin.settings.api-docs-pdf', [
            'groups' => $this->connectionPoints(),
            'generatedAt' => Carbon::now()->format('Y-m-d H:i'),
        ]);

        return $pdf->download('attendance-api-connection-points.pdf');
    }

    /**
     * Hand-written alongside routes/web.php and routes/admin.php - update
     * this when a route is added, renamed, or removed. This app has no
     * token-based JSON API; every endpoint below is a session/cookie
     * authenticated Blade route, documented here as the integration
     * surface for QR provisioning tools, kiosks, or export automation.
     */
    private function connectionPoints(): array
    {
        return [
            [
                'name' => 'QR Attendance Scan (employee-facing, no login account required)',
                'note' => 'Scoped by the location\'s QR token in the URL. No session cookie is required to view the page, but time-in/time-out require an active employee scan-session established via the login step below.',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => '/scan/{qr_token}',
                        'description' => 'Shows the location\'s scan screen. Redirects to the employee code/PIN step if no active scan-session exists for this location.',
                        'params' => 'None',
                        'response' => 'HTML page with Time In / Time Out buttons, or the sign-in form.',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/scan/{qr_token}/login',
                        'description' => 'Signs an employee into a scan-session for this location.',
                        'params' => 'Body (form): employee_code, pin. Throttled to 20 requests/minute.',
                        'response' => 'Redirect back to the scan screen, signed in.',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/scan/{qr_token}/time-in',
                        'description' => 'Records a time-in scan for the signed-in employee at this location.',
                        'params' => 'None (uses the active scan-session). Throttled to 30 requests/minute.',
                        'response' => 'Redirect back to the scan screen with a success/error flash message.',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/scan/{qr_token}/time-out',
                        'description' => 'Records a time-out scan for the signed-in employee at this location.',
                        'params' => 'None (uses the active scan-session). Throttled to 30 requests/minute.',
                        'response' => 'Redirect back to the scan screen with a success/error flash message.',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/scan/{qr_token}/logout',
                        'description' => 'Ends the employee\'s scan-session for this location (does not affect admin/manager login sessions).',
                        'params' => 'None',
                        'response' => 'Redirect back to the sign-in step.',
                    ],
                ],
            ],
            [
                'name' => 'Admin & Manager Authentication',
                'note' => 'Standard Laravel session/cookie login, not a token API. Log in once in a browser (or an HTTP client that keeps cookies) before calling anything below.',
                'endpoints' => [
                    [
                        'method' => 'POST',
                        'path' => '/login',
                        'description' => 'Authenticates an admin or manager user and starts a session.',
                        'params' => 'Body (form): email, password.',
                        'response' => 'Redirect to /dashboard with an authenticated session cookie.',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/logout',
                        'description' => 'Ends the current session.',
                        'params' => 'None',
                        'response' => 'Redirect to the login page.',
                    ],
                ],
            ],
            [
                'name' => 'Attendance & Reporting',
                'note' => 'Requires an authenticated session with role admin or manager.',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => '/admin/attendance',
                        'description' => 'Attendance log, filterable by location and date range.',
                        'params' => 'Query: location_id (admin only), from, to (dates).',
                        'response' => 'HTML page listing matching attendance scans.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/admin/attendance/export',
                        'description' => 'Downloads the same filtered attendance log as a CSV file.',
                        'params' => 'Query: location_id (admin only), from, to (dates).',
                        'response' => 'CSV file download.',
                        'auth' => true,
                    ],
                ],
            ],
            [
                'name' => 'Employee Management',
                'note' => 'Requires an authenticated session with role admin or manager.',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => '/admin/employees',
                        'description' => 'Lists employees.',
                        'params' => 'None',
                        'response' => 'HTML page.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/admin/employees',
                        'description' => 'Creates an employee.',
                        'params' => 'Body (form): name, employee_code, pin, and other employee fields.',
                        'response' => 'Redirect to the employee list.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/admin/employees/{employee}',
                        'description' => 'Updates an employee.',
                        'params' => 'Body (form): same fields as create.',
                        'response' => 'Redirect to the employee list.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/admin/employees/{employee}',
                        'description' => 'Removes an employee.',
                        'params' => 'None',
                        'response' => 'Redirect to the employee list.',
                        'auth' => true,
                    ],
                ],
            ],
            [
                'name' => 'Locations & QR Codes',
                'note' => 'Requires an authenticated session with role admin.',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => '/admin/locations',
                        'description' => 'Lists scan locations.',
                        'params' => 'None',
                        'response' => 'HTML page.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/admin/locations/{location}/qr',
                        'description' => 'Shows the printable QR code for a location, encoding its /scan/{qr_token} URL.',
                        'params' => 'None',
                        'response' => 'HTML page with an embedded QR image.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/admin/locations/{location}/regenerate-token',
                        'description' => 'Rotates a location\'s QR token, invalidating its previously printed QR code.',
                        'params' => 'None',
                        'response' => 'Redirect back with the new QR code.',
                        'auth' => true,
                    ],
                ],
            ],
            [
                'name' => 'User Management',
                'note' => 'Requires an authenticated session with role admin.',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => '/admin/users',
                        'description' => 'Lists admin/manager user accounts.',
                        'params' => 'None',
                        'response' => 'HTML page.',
                        'auth' => true,
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/admin/users',
                        'description' => 'Creates an admin/manager user account.',
                        'params' => 'Body (form): name, email, password, role.',
                        'response' => 'Redirect to the user list.',
                        'auth' => true,
                    ],
                ],
            ],
        ];
    }
}
