<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAdvance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'staff_id',
        'amount',
        'advance_date',
        'deduction_month',
        'deduction_year',
        'reason',
        'status',
    ];

    protected $casts = [
        'advance_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Auto create expense when advance is marked as deducted
     */
    public function createExpenseRecord()
    {
        $staff = $this->staff ?? Staff::find($this->staff_id);
        $staffName = $staff ? $staff->full_name : 'Employee';

        // Check or create ExpenseCategory id 8
        $category = ExpenseCategory::find(8);
        if (!$category) {
            $category = ExpenseCategory::create([
                'id' => 8,
                'name' => 'Employee Advance',
                'status' => 1,
            ]);
        }

        return Expense::create([
            'store_id' => $this->store_id ?? ($staff ? $staff->store_id : null),
            'branch_id' => $staff ? $staff->branch_id : null,
            'category_id' => 8,
            'amount' => $this->amount,
            'date' => now()->toDateString(),
            'remarks' => "advance to " . $staffName,
        ]);
    }
}
