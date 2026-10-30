<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Branch;
use Validator, Exception;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view attendances')->only(['index', 'show', 'report']);
        $this->middleware('permission:create attendances')->only(['create', 'store', 'markAttendance']);
        $this->middleware('permission:edit attendances')->only(['edit', 'update']);
        $this->middleware('permission:delete attendances')->only('destroy');
    }

    public function index()
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $isAdmin = $user->hasRole('Admin');
        
        $selectedStoreId = request('store_id', $storeId);
        $selectedBranchId = request('branch_id');
        $month = (int) request('month', Carbon::now()->month);
        if ($month < 1 || $month > 12) {
            $month = (int) Carbon::now()->month;
        }
        $year = (int) request('year', Carbon::now()->year);
        if ($year < 2000 || $year > 2100) {
            $year = (int) Carbon::now()->year;
        }

        $staffsQuery = Staff::with(['attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('date', $month)->whereYear('date', $year);
        }, 'store', 'branch']);

        if (!$isAdmin && $storeId) {
            $staffsQuery->where('store_id', $storeId);
        } elseif ($selectedStoreId) {
            $staffsQuery->where('store_id', $selectedStoreId);
        }

        if ($selectedBranchId) {
            $staffsQuery->where('branch_id', $selectedBranchId);
        }

        $staffs = $staffsQuery->where('status', true)->get();

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $stores = Store::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();

        return view('admin.attendances.index', compact('staffs', 'month', 'year', 'daysInMonth', 'stores', 'branches', 'selectedStoreId', 'selectedBranchId'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'staff_id' => 'required|exists:staffs,id',
                'date' => 'required|date',
                'status' => 'required|in:present,absent,late,half_day,holiday,leave',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $attendance = Attendance::firstOrNew(
                ['staff_id' => $request->staff_id, 'date' => $request->date]
            );

            if ($request->has('check_in')) {
                $attendance->check_in = $request->check_in;
            }
            if ($request->has('check_out')) {
                $attendance->check_out = $request->check_out;
            }
            if ($request->has('remarks')) {
                $attendance->remarks = $request->remarks;
            }
            
            $attendance->status = $request->status;
            $attendance->save();

            return response()->json(['success' => true, 'message' => 'Attendance saved successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function report()
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $isAdmin = $user->hasRole('Admin');
        
        $selectedStoreId = request('store_id', $storeId);
        $selectedBranchId = request('branch_id');
        $month = (int) request('month', Carbon::now()->month);
        if ($month < 1 || $month > 12) {
            $month = (int) Carbon::now()->month;
        }
        $year = (int) request('year', Carbon::now()->year);
        if ($year < 2000 || $year > 2100) {
            $year = (int) Carbon::now()->year;
        }

        $staffsQuery = Staff::with(['attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('date', $month)->whereYear('date', $year);
        }, 'store', 'branch']);

        if (!$isAdmin && $storeId) {
            $staffsQuery->where('store_id', $storeId);
        } elseif ($selectedStoreId) {
            $staffsQuery->where('store_id', $selectedStoreId);
        }

        if ($selectedBranchId) {
            $staffsQuery->where('branch_id', $selectedBranchId);
        }

        $staffs = $staffsQuery->where('status', true)->get();

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        
        $dates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $carbonDate = Carbon::createFromDate($year, $month, $i);
            $dates[] = [
                'day' => $i,
                'date' => $carbonDate->format('Y-m-d'),
                'day_name' => $carbonDate->format('D'),
                'is_weekend' => $carbonDate->isSunday(),
            ];
        }

        $stores = Store::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();

        return view('admin.attendances.report', compact('staffs', 'month', 'year', 'dates', 'daysInMonth', 'stores', 'branches', 'selectedStoreId', 'selectedBranchId'));
    }
}
