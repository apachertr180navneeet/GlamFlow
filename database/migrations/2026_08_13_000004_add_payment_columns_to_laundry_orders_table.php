<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ExpenseCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('laundry_orders', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('status');
            }
            if (!Schema::hasColumn('laundry_orders', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('is_paid');
            }
            if (!Schema::hasColumn('laundry_orders', 'paid_at')) {
                $table->date('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('laundry_orders', 'expense_id')) {
                $table->foreignId('expense_id')->nullable()->after('paid_at')->constrained('expenses')->nullOnDelete();
            }
        });

        // Ensure ExpenseCategory with ID 9 exists
        if (!ExpenseCategory::where('id', 9)->exists()) {
            ExpenseCategory::create([
                'id' => 9,
                'name' => 'Laundry Expense',
                'status' => 1
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            if (Schema::hasColumn('laundry_orders', 'expense_id')) {
                $table->dropForeign(['expense_id']);
                $table->dropColumn('expense_id');
            }
            if (Schema::hasColumn('laundry_orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('laundry_orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('laundry_orders', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
        });
    }
};
