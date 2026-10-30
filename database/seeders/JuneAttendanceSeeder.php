<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Attendance;
use Carbon\Carbon;

class JuneAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $staffs = Staff::where('status', true)->get();
        $year = 2026;
        $month = 6;
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $statuses = ['present', 'present', 'present', 'present', 'present', 'absent', 'half_day', 'late', 'leave', 'holiday'];

        foreach ($staffs as $staff) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                
                // Randomly pick a status but lean heavily towards present
                $status = $statuses[array_rand($statuses)];

                // Sundays are always holidays
                $dayOfWeek = Carbon::createFromDate($year, $month, $day)->dayOfWeek;
                if ($dayOfWeek == Carbon::SUNDAY) {
                    $status = 'holiday';
                }

                Attendance::updateOrCreate(
                    [
                        'staff_id' => $staff->id,
                        'date' => $date
                    ],
                    [
                        'status' => $status,
                        'check_in' => '09:00:00',
                        'check_out' => '17:00:00',
                        'remarks' => 'Seeded record'
                    ]
                );
            }
        }
    }
}
