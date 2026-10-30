<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('employee_advances')) {
            Schema::create('employee_advances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('staff_id')->constrained('staffs')->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->date('advance_date');
                $table->integer('deduction_month');
                $table->integer('deduction_year');
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'deducted', 'cancelled'])->default('pending');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn('payrolls', 'advance_amount')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->decimal('advance_amount', 10, 2)->default(0.00)->after('deductions');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_advances');

        if (Schema::hasColumn('payrolls', 'advance_amount')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->dropColumn('advance_amount');
            });
        }
    }
};
