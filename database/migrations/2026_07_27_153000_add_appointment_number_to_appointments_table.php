<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('appointments', 'appointment_number')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('appointment_number')->nullable()->after('id')->index();
            });
        }

        // Backfill existing appointments
        $appointments = DB::table('appointments')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $dailyCounts = [];
        foreach ($appointments as $app) {
            $date = \Carbon\Carbon::parse($app->appointment_date)->format('Y-m-d');
            if (!isset($dailyCounts[$date])) {
                $dailyCounts[$date] = 1;
            } else {
                $dailyCounts[$date]++;
            }

            $apptNum = sprintf('%s-%02d', $date, $dailyCounts[$date]);

            DB::table('appointments')
                ->where('id', $app->id)
                ->update(['appointment_number' => $apptNum]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('appointments', 'appointment_number')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('appointment_number');
            });
        }
    }
};
