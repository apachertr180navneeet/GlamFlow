<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Expense;
use App\Models\Service;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Exports\StoreLedgerExport;
use App\Models\Branch;
use App\Models\Store;
use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\EmployeeAdvance;
use Schema;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view reports|view sales reports')->only(['sales', 'exportSalesPdf', 'exportSalesExcel']);
        $this->middleware('permission:view reports|view customer reports')->only(['customers']);
        $this->middleware('permission:view reports|view staff reports')->only(['staff']);
        $this->middleware('permission:view reports|view expense reports')->only(['expenses']);
        $this->middleware('permission:view reports|view store ledger')->only(['storeLedger', 'exportStoreLedgerPdf', 'exportStoreLedgerExcel']);
        $this->middleware('permission:view reports|view package usage')->only(['packageUsage']);
        $this->middleware('permission:view reports|view laundry report|view laundry reports')->only(['laundry']);
        $this->middleware('permission:view reports|view outstanding report|view customer reports|view sales reports')->only(['outstanding']);
    }

    private function getStoreBranchPermissions()
    {
        $user = auth()->user();

        $isManager = $user->hasRole('Manager')
            || $user->hasRole('Store Manager')
            || in_array(strtolower($user->role ?? ''), ['manager', 'store_manager', 'store manager']);

        if ($isManager || ($user->store_id && !$user->hasRole('Admin') && !$user->hasRole('Super Admin') && !in_array(strtolower($user->role ?? ''), ['admin', 'super_admin']))) {
            $canViewAllStores = false;
            $canViewAllBranches = false;
        } else {
            $canViewAllStores = $user->hasRole('Admin')
                || $user->hasRole('Super Admin')
                || in_array(strtolower($user->role ?? ''), ['admin', 'super_admin'])
                || $user->can('view stores');

            $canViewAllBranches = $user->hasRole('Admin')
                || $user->hasRole('Super Admin')
                || in_array(strtolower($user->role ?? ''), ['admin', 'super_admin'])
                || $user->can('view branches');
        }

        return [$canViewAllStores, $canViewAllBranches];
    }

    protected function getFilteredQuery($model, $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $query = $model::query();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        if ($selectedStoreId) {
            $query->where(function ($sub) use ($selectedStoreId, $model) {
                $sub->where('store_id', $selectedStoreId);
                if ($model === Appointment::class) {
                    $sub->orWhereHas('branch', fn($bq) => $bq->where('store_id', $selectedStoreId))
                        ->orWhereHas('staff', fn($sq) => $sq->where('store_id', $selectedStoreId))
                        ->orWhereHas('appointmentServices.staff', fn($asq) => $asq->where('store_id', $selectedStoreId));
                }
            });
        }

        if ($selectedBranchId) {
            $query->where(function ($sub) use ($selectedBranchId, $model) {
                if (Schema::hasColumn((new $model)->getTable(), 'branch_id')) {
                    $sub->where('branch_id', $selectedBranchId);
                }
                if ($model === Appointment::class) {
                    $sub->orWhereHas('staff', fn($sq) => $sq->where('branch_id', $selectedBranchId))
                        ->orWhereHas('appointmentServices.staff', fn($asq) => $asq->where('branch_id', $selectedBranchId));
                }
            });
        }

        $dateFrom = $request->get('date_from') ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->get('date_to') ?: Carbon::now()->endOfMonth()->format('Y-m-d');

        $dateColumn = ($model === Expense::class) ? 'date' : 'appointment_date';

        if ($dateFrom) {
            $query->whereDate($dateColumn, '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate($dateColumn, '<=', $dateTo);
        }

        return $query;
    }

    public function sales(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $dateFrom = $request->get('date_from') ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->get('date_to') ?: Carbon::now()->endOfMonth()->format('Y-m-d');

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $baseQuery = Appointment::with(['customer', 'service', 'store', 'branch', 'appointmentServices.service', 'appointmentServices.staff'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
            ->where('status', 'completed');

        $totalSales = (clone $baseQuery)->sum('final_amount');
        $totalAppointmentsCount = (clone $baseQuery)->count();

        $appointments = (clone $baseQuery)
            ->orderBy('appointment_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $serviceWiseSales = Service::select('id', 'service_name')
            ->addSelect(['total_sales' => Appointment::selectRaw('COALESCE(SUM(final_amount), 0)')
                ->whereColumn('service_id', 'services.id')
                ->where('status', 'completed')
                ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
                ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
                ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
            ])
            ->addSelect(['total_count' => Appointment::selectRaw('COUNT(*)')
                ->whereColumn('service_id', 'services.id')
                ->where('status', 'completed')
                ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
                ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
                ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
            ])
            ->get()
            ->filter(fn($service) => $service->total_count > 0)
            ->values();

        $stores = Store::all();
        $branches = Branch::all();

        return view('admin.reports.sales', compact('appointments', 'totalSales', 'totalAppointmentsCount', 'serviceWiseSales', 'stores', 'branches', 'dateFrom', 'dateTo', 'selectedStoreId', 'selectedBranchId', 'canViewAllStores', 'canViewAllBranches'));
    }

    public function customers(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $customers = Customer::withCount(['appointments' => function ($q) use ($selectedStoreId, $selectedBranchId) {
            $q->where('status', 'completed')
              ->when($selectedStoreId, fn($sq) => $sq->where('store_id', $selectedStoreId))
              ->when($selectedBranchId, fn($sq) => $sq->where('branch_id', $selectedBranchId));
        }])
        ->withMax(['appointments as last_visit' => function ($q) use ($selectedStoreId, $selectedBranchId) {
            $q->where('status', 'completed')
              ->when($selectedStoreId, fn($sq) => $sq->where('store_id', $selectedStoreId))
              ->when($selectedBranchId, fn($sq) => $sq->where('branch_id', $selectedBranchId));
        }], 'appointment_date')
            ->when($selectedStoreId || $selectedBranchId, function($q) use ($selectedStoreId, $selectedBranchId) {
                $q->whereHas('appointments', function($aq) use ($selectedStoreId, $selectedBranchId) {
                    $aq->when($selectedStoreId, fn($sq) => $sq->where('store_id', $selectedStoreId))
                       ->when($selectedBranchId, fn($sq) => $sq->where('branch_id', $selectedBranchId));
                });
            })
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderBy('appointments_count', 'desc')
            ->paginate(20)
            ->withQueryString();

        $newCustomers = (clone $customers)->where('created_at', '>=', Carbon::now()->subDays(30));
        $repeatCustomers = $customers->filter(fn($c) => $c->appointments_count > 1);

        $stores = Store::all();
        $branches = Branch::all();

        return view('admin.reports.customers', compact('customers', 'newCustomers', 'repeatCustomers', 'stores', 'branches', 'selectedStoreId', 'selectedBranchId', 'canViewAllStores', 'canViewAllBranches'));
    }

    public function staff(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $query = Staff::with(['attendances', 'payrolls', 'store', 'branch', 'advances'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where(fn($bq) => $bq->where('branch_id', $selectedBranchId)->orWhereNull('branch_id')));

        $staffs = $query->get();

        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;

        // Fetch completed appointments
        $appointmentsQuery = Appointment::with(['appointmentServices'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->where('status', 'completed');

        if ($dateFrom) {
            $appointmentsQuery->whereDate('appointment_date', '>=', $dateFrom->format('Y-m-d'));
        }
        if ($dateTo) {
            $appointmentsQuery->whereDate('appointment_date', '<=', $dateTo->format('Y-m-d'));
        }

        $appointments = $appointmentsQuery->get();

        // Calculate sales amount, completed services count, and employee advances for each staff member
        foreach ($staffs as $staff) {
            $totalSales = 0.0;
            $servicesCount = 0;

            foreach ($appointments as $apt) {
                if ($apt->appointmentServices && $apt->appointmentServices->count() > 0) {
                    foreach ($apt->appointmentServices as $item) {
                        $staffIds = $item->staff_ids;
                        if (!is_array($staffIds) || empty($staffIds)) {
                            if ($item->staff_id) {
                                $staffIds = [$item->staff_id];
                            } else {
                                $staffIds = [];
                            }
                        }

                        if (in_array($staff->id, $staffIds)) {
                            $staffCount = count($staffIds);
                            $price = (float)$item->price;
                            $splitPrice = $staffCount > 0 ? ($price / $staffCount) : 0;
                            $totalSales += $splitPrice;
                            $servicesCount++;
                        }
                    }
                } else {
                    // Fallback for legacy single-service appointment
                    if ($apt->staff_id == $staff->id) {
                        $totalSales += (float)$apt->final_amount;
                        $servicesCount++;
                    }
                }
            }

            $staff->total_sales_amount = $totalSales;
            $staff->completed_services_count = $servicesCount;

            // Calculate Employee Advances (filtering by date if provided)
            $staffAdvances = $staff->advances;
            if ($dateFrom || $dateTo) {
                $staffAdvances = $staffAdvances->filter(function($adv) use ($dateFrom, $dateTo) {
                    $advDate = Carbon::parse($adv->advance_date);
                    if ($dateFrom && $advDate->lt($dateFrom)) return false;
                    if ($dateTo && $advDate->gt($dateTo)) return false;
                    return true;
                });
            }

            $staff->total_advance_amount = (float) $staffAdvances->sum('amount');
            $staff->pending_advance_amount = (float) $staffAdvances->where('status', 'pending')->sum('amount');
            $staff->deducted_advance_amount = (float) $staffAdvances->where('status', 'deducted')->sum('amount');
            $staff->advances_count = $staffAdvances->count();
        }

        $staffs = $staffs->sortByDesc('total_sales_amount')->values();
        $stores = Store::all();
        $branches = Branch::all();

        $totalAdvanceAll = $staffs->sum('total_advance_amount');
        $totalPendingAdvanceAll = $staffs->sum('pending_advance_amount');
        $totalDeductedAdvanceAll = $staffs->sum('deducted_advance_amount');

        return view('admin.reports.staff', compact('staffs', 'stores', 'branches', 'selectedStoreId', 'selectedBranchId', 'canViewAllStores', 'canViewAllBranches', 'totalAdvanceAll', 'totalPendingAdvanceAll', 'totalDeductedAdvanceAll'));
    }

    public function expenses(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $expenses = Expense::with(['category', 'store', 'branch'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($request->date_from, fn($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('date', '<=', $request->date_to))
            ->orderBy('date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $categoryWise = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $stores = Store::all();
        $branches = Branch::all();

        return view('admin.reports.expenses', compact('expenses', 'categoryWise', 'totalExpenses', 'stores', 'branches', 'selectedStoreId', 'selectedBranchId', 'canViewAllStores', 'canViewAllBranches'));
    }

    public function laundry(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');
        $selectedStatus = $request->get('status');

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Base query for filtered Laundry Orders
        $query = LaundryOrder::with(['store', 'branch', 'items.item'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($selectedStatus, fn($q) => $q->where('status', $selectedStatus))
            ->when($dateFrom, fn($q) => $q->whereDate('order_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('order_date', '<=', $dateTo));

        // Metric summaries
        $totalOrdersCount = (clone $query)->count();
        $totalAmount = (clone $query)->sum('total_amount');

        $orderIds = (clone $query)->pluck('id');

        $totalSentQty = LaundryOrderItem::whereIn('laundry_order_id', $orderIds)->sum('qty');
        $totalReceivedQty = LaundryOrderItem::whereIn('laundry_order_id', $orderIds)->sum('received_qty');

        $pendingOrdersCount = (clone $query)->where(fn($q) => $q->whereNull('status')->orWhere('status', 'pending'))->count();
        $partiallyReceivedCount = (clone $query)->where('status', 'partially_received')->count();
        $receivedCount = (clone $query)->where('status', 'received')->count();

        // Item-wise breakdown table query
        $itemBreakdown = LaundryOrderItem::whereIn('laundry_order_id', $orderIds)
            ->select(
                'item_id',
                DB::raw('SUM(qty) as total_sent'),
                DB::raw('SUM(received_qty) as total_received'),
                DB::raw('SUM(total) as total_amount')
            )
            ->with('item')
            ->groupBy('item_id')
            ->get();

        // Paginated orders list
        $laundryOrders = (clone $query)
            ->orderBy('order_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $stores = Store::all();
        $branches = Branch::all();

        return view('admin.reports.laundry', compact(
            'laundryOrders',
            'itemBreakdown',
            'totalOrdersCount',
            'totalAmount',
            'totalSentQty',
            'totalReceivedQty',
            'pendingOrdersCount',
            'partiallyReceivedCount',
            'receivedCount',
            'stores',
            'branches',
            'dateFrom',
            'dateTo',
            'selectedStoreId',
            'selectedBranchId',
            'selectedStatus',
            'canViewAllStores',
            'canViewAllBranches'
        ));
    }

    public function exportSalesPdf(Request $request)
    {
        $dateFrom = $request->get('date_from') ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->get('date_to') ?: Carbon::now()->endOfMonth()->format('Y-m-d');

        $appointments = $this->getFilteredQuery(Appointment::class, $request)
            ->with(['customer', 'service', 'store', 'branch', 'appointmentServices.service', 'appointmentServices.staff'])
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.reports.exports.sales-pdf', compact('appointments', 'dateFrom', 'dateTo'));
        return $pdf->download('sales-report.pdf');
    }

    public function exportSalesExcel(Request $request)
    {
        return Excel::download(new SalesReportExport($request), 'sales-report.xlsx');
    }

    public function storeLedger(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        // Default to current date (today) if date_from or date_to is not specified
        $dateFrom = $request->get('date_from') ?: Carbon::today()->format('Y-m-d');
        $dateTo = $request->get('date_to') ?: Carbon::today()->format('Y-m-d');

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        // Query completed sales appointments
        $salesQuery = Appointment::with(['customer', 'service', 'store', 'branch', 'appointmentServices.service', 'appointmentServices.staff'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
            ->where('status', 'completed');

        // Query expenses
        $expensesQuery = Expense::with(['category', 'store', 'branch'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('date', '<=', $dateTo));

        $totalSales = (clone $salesQuery)->sum('final_amount');
        $totalCashSales = (clone $salesQuery)->sum('cash_amount');
        $totalCashCount = (clone $salesQuery)->where(function($q) {
            $q->where('cash_amount', '>', 0)->orWhere(fn($q2) => $q2->whereRaw('LOWER(payment_type) = ?', ['cash'])->where('paid_amount', '>', 0));
        })->count();
        $totalUpiSales = (clone $salesQuery)->sum('upi_amount');
        $totalUpiCount = (clone $salesQuery)->where(function($q) {
            $q->where('upi_amount', '>', 0)->orWhere(fn($q2) => $q2->whereRaw('LOWER(payment_type) = ?', ['upi'])->where('paid_amount', '>', 0));
        })->count();
        $totalExpenses = (clone $expensesQuery)->sum('amount');
        $netBalance = $totalSales - $totalExpenses;

        $salesList = (clone $salesQuery)->get()->map(function ($item) {
            $pType = $item->payment_type ? ucfirst($item->payment_type) : 'N/A';
            if (strtolower($item->payment_type) === 'split') {
                $parts = [];
                if ((float)$item->cash_amount > 0) $parts[] = 'Cash: ₹' . number_format($item->cash_amount, 2);
                if ((float)$item->upi_amount > 0) $parts[] = 'UPI: ₹' . number_format($item->upi_amount, 2);
                if ((float)$item->card_amount > 0) $parts[] = 'Card: ₹' . number_format($item->card_amount, 2);
                $pType = 'Split (' . implode(', ', $parts) . ')';
            }

            $serviceDetails = [];
            if ($item->appointmentServices && $item->appointmentServices->count() > 0) {
                foreach ($item->appointmentServices as $appService) {
                    $sName = $appService->service->service_name ?? 'Service';
                    $staffMembers = $appService->staffMembers();
                    $stNames = $staffMembers->count() > 0 
                        ? $staffMembers->pluck('full_name')->implode(', ') 
                        : ($appService->staff->full_name ?? $appService->staff->name ?? '');
                    
                    $serviceDetails[] = !empty($stNames) ? "{$sName} ({$stNames})" : $sName;
                }
            } else {
                $sName = $item->service->service_name ?? 'Service';
                $stName = $item->staff->full_name ?? $item->staff->name ?? '';
                $serviceDetails[] = !empty($stName) ? "{$sName} ({$stName})" : $sName;
            }
            $servicesText = !empty($serviceDetails) ? implode(', ', $serviceDetails) : 'Service';
            $customerName = $item->customer->name ?? $item->customer->full_name ?? 'Customer';

            return (object)[
                'type' => 'Sale',
                'date' => $item->appointment_date ?? $item->created_at,
                'store_name' => $item->store->store_name ?? $item->store->name ?? 'N/A',
                'branch_name' => $item->branch->name ?? 'N/A',
                'store_id' => $item->store_id,
                'branch_id' => $item->branch_id,
                'ref_no' => $item->appointment_number ?? ('APT-' . $item->id),
                'details' => $customerName . ' - ' . $servicesText,
                'payment_type' => $pType,
                'upi_reference' => $item->upi_reference,
                'credit' => (float)$item->final_amount,
                'debit' => 0.00,
            ];
        });

        $expensesList = (clone $expensesQuery)->get()->map(function ($item) {
            return (object)[
                'type' => 'Expense',
                'date' => $item->date,
                'store_name' => $item->store->store_name ?? $item->store->name ?? 'N/A',
                'branch_name' => $item->branch->name ?? 'N/A',
                'store_id' => $item->store_id,
                'branch_id' => $item->branch_id,
                'ref_no' => 'EXP-' . $item->id,
                'details' => ($item->category->name ?? 'Expense') . ($item->remarks ? ' (' . $item->remarks . ')' : ''),
                'payment_type' => 'Cash/Bank',
                'upi_reference' => null,
                'credit' => 0.00,
                'debit' => (float)$item->amount,
            ];
        });

        $combinedLedger = $salesList->concat($expensesList)->sortByDesc(function ($entry) {
            return $entry->date ? Carbon::parse($entry->date)->timestamp : 0;
        })->values();

        $stores = Store::all();
        $branches = Branch::all();

        $breakdownStores = $selectedStoreId ? $stores->where('id', $selectedStoreId) : $stores;

        $storeWiseBreakdown = $breakdownStores->map(function ($s) use ($dateFrom, $dateTo, $selectedBranchId) {
            $stSalesQuery = Appointment::where('store_id', $s->id)
                ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
                ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
                ->where('status', 'completed');

            $stExpensesQuery = Expense::where('store_id', $s->id)
                ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
                ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('date', '<=', $dateTo));

            $stSales = (clone $stSalesQuery)->sum('final_amount');
            $stCash = (clone $stSalesQuery)->sum('cash_amount');
            $stCashCount = (clone $stSalesQuery)->where(function($q) {
                $q->where('cash_amount', '>', 0)->orWhere(fn($q2) => $q2->whereRaw('LOWER(payment_type) = ?', ['cash'])->where('paid_amount', '>', 0));
            })->count();
            $stUpi = (clone $stSalesQuery)->sum('upi_amount');
            $stUpiCount = (clone $stSalesQuery)->where(function($q) {
                $q->where('upi_amount', '>', 0)->orWhere(fn($q2) => $q2->whereRaw('LOWER(payment_type) = ?', ['upi'])->where('paid_amount', '>', 0));
            })->count();
            $stExpenses = (clone $stExpensesQuery)->sum('amount');

            return (object) [
                'store_name' => $s->store_name ?? $s->name,
                'total_sales' => $stSales,
                'cash_sales' => $stCash,
                'cash_count' => $stCashCount,
                'upi_sales' => $stUpi,
                'upi_count' => $stUpiCount,
                'total_expenses' => $stExpenses,
                'net_balance' => $stSales - $stExpenses,
            ];
        });

        return view('admin.reports.store_ledger', compact(
            'combinedLedger', 'totalSales', 'totalCashSales', 'totalCashCount', 'totalUpiSales', 'totalUpiCount', 'totalExpenses', 'netBalance',
            'stores', 'branches', 'storeWiseBreakdown', 'dateFrom', 'dateTo', 'selectedStoreId', 'selectedBranchId', 'canViewAllStores', 'canViewAllBranches'
        ));
    }

    public function exportStoreLedgerPdf(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $dateFrom = $request->get('date_from') ?: Carbon::today()->format('Y-m-d');
        $dateTo = $request->get('date_to') ?: Carbon::today()->format('Y-m-d');
        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $salesQuery = Appointment::with(['customer', 'service', 'store', 'branch', 'appointmentServices.service', 'appointmentServices.staff'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
            ->where('status', 'completed');

        $expensesQuery = Expense::with(['category', 'store', 'branch'])
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('date', '<=', $dateTo));

        $totalSales = (clone $salesQuery)->sum('final_amount');
        $totalCashSales = (clone $salesQuery)->whereRaw('LOWER(payment_type) = ?', ['cash'])->sum('final_amount');
        $totalCashCount = (clone $salesQuery)->whereRaw('LOWER(payment_type) = ?', ['cash'])->count();
        $totalUpiSales = (clone $salesQuery)->whereRaw('LOWER(payment_type) = ?', ['upi'])->sum('final_amount');
        $totalUpiCount = (clone $salesQuery)->whereRaw('LOWER(payment_type) = ?', ['upi'])->count();
        $totalExpenses = (clone $expensesQuery)->sum('amount');
        $netBalance = $totalSales - $totalExpenses;

        $salesList = (clone $salesQuery)->get()->map(function ($item) {
            $serviceDetails = [];
            if ($item->appointmentServices && $item->appointmentServices->count() > 0) {
                foreach ($item->appointmentServices as $appService) {
                    $sName = $appService->service->service_name ?? 'Service';
                    $staffMembers = $appService->staffMembers();
                    $stNames = $staffMembers->count() > 0 
                        ? $staffMembers->pluck('full_name')->implode(', ') 
                        : ($appService->staff->full_name ?? $appService->staff->name ?? '');
                    
                    $serviceDetails[] = !empty($stNames) ? "{$sName} ({$stNames})" : $sName;
                }
            } else {
                $sName = $item->service->service_name ?? 'Service';
                $stName = $item->staff->full_name ?? $item->staff->name ?? '';
                $serviceDetails[] = !empty($stName) ? "{$sName} ({$stName})" : $sName;
            }
            $servicesText = !empty($serviceDetails) ? implode(', ', $serviceDetails) : 'Service';
            $customerName = $item->customer->name ?? $item->customer->full_name ?? 'Customer';

            return (object)[
                'type' => 'Sale',
                'date' => $item->appointment_date ?? $item->created_at,
                'store_name' => $item->store->store_name ?? $item->store->name ?? 'N/A',
                'branch_name' => $item->branch->name ?? 'N/A',
                'ref_no' => $item->appointment_number ?? ('APT-' . $item->id),
                'details' => $customerName . ' - ' . $servicesText,
                'payment_type' => $item->payment_type ? ucfirst($item->payment_type) : 'N/A',
                'upi_reference' => $item->upi_reference,
                'credit' => (float)$item->final_amount,
                'debit' => 0.00,
            ];
        });

        $expensesList = (clone $expensesQuery)->get()->map(function ($item) {
            return (object)[
                'type' => 'Expense',
                'date' => $item->date,
                'store_name' => $item->store->store_name ?? $item->store->name ?? 'N/A',
                'branch_name' => $item->branch->name ?? 'N/A',
                'ref_no' => 'EXP-' . $item->id,
                'details' => ($item->category->name ?? 'Expense') . ($item->remarks ? ' (' . $item->remarks . ')' : ''),
                'payment_type' => 'Cash/Bank',
                'upi_reference' => null,
                'credit' => 0.00,
                'debit' => (float)$item->amount,
            ];
        });

        $combinedLedger = $salesList->concat($expensesList)->sortByDesc(function ($entry) {
            return $entry->date ? Carbon::parse($entry->date)->timestamp : 0;
        })->values();

        $pdf = Pdf::loadView('admin.reports.exports.store-ledger-pdf', compact(
            'combinedLedger', 'totalSales', 'totalCashSales', 'totalCashCount', 'totalUpiSales', 'totalUpiCount', 'totalExpenses', 'netBalance', 'dateFrom', 'dateTo'
        ));

        return $pdf->download('store-ledger-report.pdf');
    }

    public function exportStoreLedgerExcel(Request $request)
    {
        return Excel::download(new StoreLedgerExport($request), 'store-ledger-report.xlsx');
    }

    public function packageUsage(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $customerId = $request->get('customer_id');
        $statusFilter = $request->get('status');

        $query = Package::with(['customer', 'duration']);

        if ($selectedStoreId || $selectedBranchId) {
            $query->whereHas('customer', function($cq) use ($selectedStoreId, $selectedBranchId) {
                $cq->whereHas('appointments', function($aq) use ($selectedStoreId, $selectedBranchId) {
                    $aq->when($selectedStoreId, fn($sq) => $sq->where('store_id', $selectedStoreId))
                       ->when($selectedBranchId, fn($sq) => $sq->where('branch_id', $selectedBranchId));
                });
            });
        }

        if ($dateFrom) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('start_date', '<=', $dateTo);
        }
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $allPackages = $query->latest()->get();

        // Calculate usage for each package
        $packages = $allPackages->map(function ($pkg) {
            $usage = $pkg->getUsageDetails();
            $pkg->usage_data = $usage;
            $pkg->calculated_status = $usage['status'];
            return $pkg;
        });

        if ($statusFilter) {
            $packages = $packages->filter(function ($pkg) use ($statusFilter) {
                return strtolower($pkg->calculated_status) === strtolower($statusFilter);
            })->values();
        }

        $totalPackages = $packages->count();
        $totalAllocatedQty = $packages->sum(fn($p) => $p->usage_data['total_allocated_qty'] ?? 0);
        $totalUsedQty = $packages->sum(fn($p) => $p->usage_data['total_used_qty'] ?? 0);
        $totalRemainingQty = $packages->sum(fn($p) => $p->usage_data['total_remaining_qty'] ?? 0);

        $customers = Customer::orderBy('name')->get();
        $services = Service::all()->keyBy('id');
        $stores = Store::all();
        $branches = Branch::all();

        return view('admin.reports.package_usage', compact(
            'packages', 'customers', 'services', 'stores', 'branches', 'dateFrom', 'dateTo',
            'customerId', 'statusFilter', 'totalPackages', 'totalAllocatedQty',
            'totalUsedQty', 'totalRemainingQty', 'selectedStoreId', 'selectedBranchId', 'canViewAllStores', 'canViewAllBranches'
        ));
    }

    public function outstanding(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;
        $branchId = $user->branch_id;
        [$canViewAllStores, $canViewAllBranches] = $this->getStoreBranchPermissions();

        $selectedStoreId = ($storeId && !$canViewAllStores) ? $storeId : $request->get('store_id');
        $selectedBranchId = ($branchId && !$canViewAllBranches) ? $branchId : $request->get('branch_id');

        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;
        $search = $request->get('search');
        $activeTab = $request->get('tab', 'customers');

        // 1. Outstanding Appointments (final_amount > paid_amount and not cancelled)
        $appointmentsQuery = Appointment::with(['customer', 'store', 'branch', 'service', 'staff', 'appointmentServices.service'])
            ->where('status', '!=', 'cancelled')
            ->whereRaw('COALESCE(final_amount, 0) > COALESCE(paid_amount, 0)')
            ->when($selectedStoreId, fn($q) => $q->where('store_id', $selectedStoreId))
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId));

        if ($dateFrom) {
            $appointmentsQuery->whereDate('appointment_date', '>=', $dateFrom->format('Y-m-d'));
        }
        if ($dateTo) {
            $appointmentsQuery->whereDate('appointment_date', '<=', $dateTo->format('Y-m-d'));
        }
        if ($search) {
            $appointmentsQuery->where(function($q) use ($search) {
                $q->where('appointment_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
            });
        }

        $outstandingAppointments = $appointmentsQuery->orderBy('appointment_date', 'desc')->get();
        $totalAppointmentDue = (float) $outstandingAppointments->sum(fn($a) => max(0, (float)$a->final_amount - (float)$a->paid_amount));

        // 2. Outstanding Packages (remaining > 0)
        $packagesQuery = Package::with(['customer', 'duration'])
            ->where('remaining', '>', 0);

        if ($dateFrom) {
            $packagesQuery->whereDate('created_at', '>=', $dateFrom->format('Y-m-d'));
        }
        if ($dateTo) {
            $packagesQuery->whereDate('created_at', '<=', $dateTo->format('Y-m-d'));
        }
        if ($search) {
            $packagesQuery->whereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
        }

        $outstandingPackages = $packagesQuery->orderBy('created_at', 'desc')->get();
        $totalPackageDue = (float) $outstandingPackages->sum('remaining');

        // 3. Group Customer Outstanding Summary (combining Appointments + Packages)
        $customerSummaryMap = [];

        foreach ($outstandingAppointments as $apt) {
            $cId = $apt->customer_id ?? 0;
            if (!$cId && !$apt->customer) continue;

            if (!isset($customerSummaryMap[$cId])) {
                $customerSummaryMap[$cId] = [
                    'customer_id' => $cId,
                    'customer' => $apt->customer,
                    'customer_name' => $apt->customer->name ?? 'Customer #' . $cId,
                    'customer_phone' => $apt->customer->phone ?? 'N/A',
                    'customer_email' => $apt->customer->email ?? '',
                    'store_name' => $apt->store->store_name ?? $apt->store->name ?? 'N/A',
                    'branch_name' => $apt->branch->name ?? 'N/A',
                    'appointment_count' => 0,
                    'appointment_total' => 0.0,
                    'appointment_paid' => 0.0,
                    'appointment_due' => 0.0,
                    'package_count' => 0,
                    'package_total' => 0.0,
                    'package_paid' => 0.0,
                    'package_due' => 0.0,
                    'total_due' => 0.0,
                    'last_date' => $apt->appointment_date ? Carbon::parse($apt->appointment_date)->format('Y-m-d') : 'N/A',
                ];
            }

            $aptDue = max(0, (float)$apt->final_amount - (float)$apt->paid_amount);
            $customerSummaryMap[$cId]['appointment_count']++;
            $customerSummaryMap[$cId]['appointment_total'] += (float)$apt->final_amount;
            $customerSummaryMap[$cId]['appointment_paid'] += (float)$apt->paid_amount;
            $customerSummaryMap[$cId]['appointment_due'] += $aptDue;
            $customerSummaryMap[$cId]['total_due'] += $aptDue;

            if ($apt->appointment_date && $customerSummaryMap[$cId]['last_date'] !== 'N/A') {
                if (Carbon::parse($apt->appointment_date)->gt(Carbon::parse($customerSummaryMap[$cId]['last_date']))) {
                    $customerSummaryMap[$cId]['last_date'] = Carbon::parse($apt->appointment_date)->format('Y-m-d');
                }
            }
        }

        foreach ($outstandingPackages as $pkg) {
            $cId = $pkg->customer_id ?? 0;
            if (!$cId && !$pkg->customer) continue;

            if (!isset($customerSummaryMap[$cId])) {
                $customerSummaryMap[$cId] = [
                    'customer_id' => $cId,
                    'customer' => $pkg->customer,
                    'customer_name' => $pkg->customer->name ?? 'Customer #' . $cId,
                    'customer_phone' => $pkg->customer->phone ?? 'N/A',
                    'customer_email' => $pkg->customer->email ?? '',
                    'store_name' => 'N/A',
                    'branch_name' => 'N/A',
                    'appointment_count' => 0,
                    'appointment_total' => 0.0,
                    'appointment_paid' => 0.0,
                    'appointment_due' => 0.0,
                    'package_count' => 0,
                    'package_total' => 0.0,
                    'package_paid' => 0.0,
                    'package_due' => 0.0,
                    'total_due' => 0.0,
                    'last_date' => $pkg->created_at ? Carbon::parse($pkg->created_at)->format('Y-m-d') : 'N/A',
                ];
            }

            $pkgDue = (float)$pkg->remaining;
            $customerSummaryMap[$cId]['package_count']++;
            $customerSummaryMap[$cId]['package_total'] += (float)$pkg->amount;
            $customerSummaryMap[$cId]['package_paid'] += (float)$pkg->advance;
            $customerSummaryMap[$cId]['package_due'] += $pkgDue;
            $customerSummaryMap[$cId]['total_due'] += $pkgDue;

            if ($pkg->created_at && $customerSummaryMap[$cId]['last_date'] !== 'N/A') {
                if (Carbon::parse($pkg->created_at)->gt(Carbon::parse($customerSummaryMap[$cId]['last_date']))) {
                    $customerSummaryMap[$cId]['last_date'] = Carbon::parse($pkg->created_at)->format('Y-m-d');
                }
            }
        }

        $customerSummaries = collect($customerSummaryMap)->sortByDesc('total_due')->values();

        $totalCustomersDueCount = $customerSummaries->count();
        $grandTotalOutstanding = $totalAppointmentDue + $totalPackageDue;

        $stores = Store::all();
        $branches = Branch::all();

        return view('admin.reports.outstanding', compact(
            'customerSummaries',
            'outstandingAppointments',
            'outstandingPackages',
            'totalAppointmentDue',
            'totalPackageDue',
            'grandTotalOutstanding',
            'totalCustomersDueCount',
            'stores',
            'branches',
            'selectedStoreId',
            'selectedBranchId',
            'canViewAllStores',
            'canViewAllBranches',
            'dateFrom',
            'dateTo',
            'search',
            'activeTab'
        ));
    }
}
