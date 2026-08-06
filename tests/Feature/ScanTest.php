<?php

namespace Tests\Feature;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanTest extends TestCase
{
    use RefreshDatabase;

    private function location(): Location
    {
        return Location::factory()->create();
    }

    public function test_inactive_location_scan_page_returns_404(): void
    {
        $location = Location::factory()->inactive()->create();

        $this->get("/scan/{$location->qr_token}")->assertNotFound();
    }

    public function test_scan_page_shows_login_form_for_guest(): void
    {
        $location = $this->location();

        $this->get("/scan/{$location->qr_token}")
            ->assertOk()
            ->assertSee('Employee code');
    }

    public function test_employee_can_login_with_valid_credentials(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id, 'pin_hash' => bcrypt('4321')]);

        $this->post("/scan/{$location->qr_token}/login", [
            'employee_code' => $employee->employee_code,
            'pin' => '4321',
        ])->assertRedirect("/scan/{$location->qr_token}");

        $this->assertAuthenticated('employee');
    }

    public function test_employee_cannot_login_with_invalid_pin(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id]);

        $this->post("/scan/{$location->qr_token}/login", [
            'employee_code' => $employee->employee_code,
            'pin' => '9999',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest('employee');
    }

    public function test_inactive_employee_cannot_login(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->inactive()->create(['home_location_id' => $location->id]);

        $this->post("/scan/{$location->qr_token}/login", [
            'employee_code' => $employee->employee_code,
            'pin' => '1234',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest('employee');
    }

    public function test_time_in_requires_an_employee_session(): void
    {
        $location = $this->location();

        $this->post("/scan/{$location->qr_token}/time-in")->assertForbidden();
    }

    public function test_time_in_records_an_attendance_log(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id]);

        $this->actingAs($employee, 'employee')
            ->post("/scan/{$location->qr_token}/time-in", [], ['Referer' => url("/scan/{$location->qr_token}")])
            ->assertRedirect("/scan/{$location->qr_token}")
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_logs', [
            'employee_id' => $employee->id,
            'location_id' => $location->id,
            'type' => 'time_in',
        ]);
    }

    public function test_double_time_in_is_rejected(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id]);

        AttendanceLog::create([
            'employee_id' => $employee->id,
            'location_id' => $location->id,
            'type' => 'time_in',
            'scanned_at' => now(),
        ]);

        $this->actingAs($employee, 'employee')
            ->post("/scan/{$location->qr_token}/time-in", [], ['Referer' => url("/scan/{$location->qr_token}")])
            ->assertRedirect("/scan/{$location->qr_token}")
            ->assertSessionHas('error');

        $this->assertDatabaseCount('attendance_logs', 1);
    }

    public function test_time_out_without_time_in_is_rejected(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id]);

        $this->actingAs($employee, 'employee')
            ->post("/scan/{$location->qr_token}/time-out", [], ['Referer' => url("/scan/{$location->qr_token}")])
            ->assertRedirect("/scan/{$location->qr_token}")
            ->assertSessionHas('error');

        $this->assertDatabaseCount('attendance_logs', 0);
    }

    public function test_time_out_after_time_in_records_a_log(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id]);

        $this->actingAs($employee, 'employee')->post("/scan/{$location->qr_token}/time-in", [], ['Referer' => url("/scan/{$location->qr_token}")]);

        $this->actingAs($employee, 'employee')
            ->post("/scan/{$location->qr_token}/time-out", [], ['Referer' => url("/scan/{$location->qr_token}")])
            ->assertRedirect("/scan/{$location->qr_token}")
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_logs', [
            'employee_id' => $employee->id,
            'type' => 'time_out',
        ]);
    }

    public function test_employee_can_logout_of_scan_session(): void
    {
        $location = $this->location();
        $employee = Employee::factory()->create(['home_location_id' => $location->id]);

        $this->actingAs($employee, 'employee')
            ->post("/scan/{$location->qr_token}/logout")
            ->assertRedirect("/scan/{$location->qr_token}");

        $this->assertGuest('employee');
    }
}
