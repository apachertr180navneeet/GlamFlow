<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\DurationController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\SmsLogController;
use App\Http\Controllers\Admin\WhatsappLogController;
use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\EmployeeAdvanceController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LaundryOrderController;

Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'index']);
    Route::get('login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('login', [AdminAuthController::class, 'postLogin'])->name('login.post');
    Route::get('forget-password', [AdminAuthController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [AdminAuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [AdminAuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');

    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('change-password', [AdminAuthController::class, 'changePassword'])->name('change.password');
        Route::post('update-password', [AdminAuthController::class, 'updatePassword'])->name('update.password');
        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AdminAuthController::class, 'adminProfile'])->name('profile');
        Route::post('profile', [AdminAuthController::class, 'updateAdminProfile'])->name('update.profile');

        // Users
        Route::resource('users', UserController::class);

        // Roles
        Route::resource('roles', RoleController::class);

        // Permissions
        Route::resource('permissions', PermissionController::class);

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Stores
        Route::resource('stores', StoreController::class);
        Route::post('stores/{id}/toggle-status', [StoreController::class, 'toggleStatus'])->name('stores.toggle-status');

        // Branches
        Route::resource('branches', BranchController::class);
        Route::post('branches/{id}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status');

        // Customers
        Route::get('customers/ajax-search', [CustomerController::class, 'ajaxSearch'])->name('customers.ajax-search');
        Route::resource('customers', CustomerController::class);
        Route::get('birthday-customers', [CustomerController::class, 'getBirthdayCustomers'])->name('customers.birthdays');
        Route::post('birthday-whatsapp-log', [CustomerController::class, 'storeBirthdayWhatsappLog'])->name('customers.birthday-whatsapp-log');
        Route::get('birthday-msg-logs', [CustomerController::class, 'birthdayWhatsappLogs'])->name('customers.birthday-msg-logs');

        // Service Categories
        Route::get('service-categories', [ServiceCategoryController::class, 'index'])->name('service-categories.index');
        Route::post('service-categories', [ServiceCategoryController::class, 'store'])->name('service-categories.store');
        Route::get('service-categories/{id}/edit', [ServiceCategoryController::class, 'edit'])->name('service-categories.edit');
        Route::put('service-categories/{id}', [ServiceCategoryController::class, 'update'])->name('service-categories.update');
        Route::delete('service-categories/{id}', [ServiceCategoryController::class, 'destroy'])->name('service-categories.destroy');
        Route::post('service-categories/{id}/toggle-status', [ServiceCategoryController::class, 'toggleStatus'])->name('service-categories.toggle-status');

        // Durations
        Route::get('durations', [DurationController::class, 'index'])->name('durations.index');
        Route::post('durations', [DurationController::class, 'store'])->name('durations.store');
        Route::get('durations/{id}/edit', [DurationController::class, 'edit'])->name('durations.edit');
        Route::put('durations/{id}', [DurationController::class, 'update'])->name('durations.update');
        Route::delete('durations/{id}', [DurationController::class, 'destroy'])->name('durations.destroy');
        Route::post('durations/{id}/toggle-status', [DurationController::class, 'toggleStatus'])->name('durations.toggle-status');

        // Services
        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{id}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
        Route::post('services/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::get('services-by-category/{categoryId}', [ServiceController::class, 'getByCategory'])->name('services.by-category');

        // Appointments
        Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::get('appointments/customer-packages', [AppointmentController::class, 'getCustomerPackages'])->name('appointments.customer-packages');
        Route::get('appointments/customer-balance', [AppointmentController::class, 'getCustomerBalance'])->name('appointments.customer-balance');
        Route::get('appointments/ajax/search-customers', [AppointmentController::class, 'ajaxSearchCustomers'])->name('appointments.search-customers');
        Route::get('appointments/ajax/search-services', [AppointmentController::class, 'ajaxSearchServices'])->name('appointments.search-services');
        Route::get('appointments/ajax/search-staffs', [AppointmentController::class, 'ajaxSearchStaffs'])->name('appointments.search-staffs');
        Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::post('appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

        // Packages
        Route::resource('packages', PackageController::class);
        Route::post('packages/{package}/add-payment', [PackageController::class, 'addPayment'])->name('packages.add-payment');
        Route::get('packages/{package}/usage-details', [PackageController::class, 'usageDetails'])->name('packages.usage-details');

        // Staff
        Route::resource('staffs', StaffController::class);
        Route::post('staffs/{id}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staffs.toggle-status');

        // Holidays
        Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');
        Route::post('holidays', [HolidayController::class, 'store'])->name('holidays.store');
        Route::get('holidays/{id}/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
        Route::put('holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update');
        Route::delete('holidays/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

        // Attendance
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendance-report', [AttendanceController::class, 'report'])->name('attendances.report');

        // Leave Requests
        Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::post('leave-requests/{id}/status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update-status');
        Route::delete('leave-requests/{id}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');


        // Payroll
        Route::get('payrolls/calculate', [PayrollController::class, 'calculate'])->name('payrolls.calculate');
        Route::post('payrolls/generate-all', [PayrollController::class, 'generateAll'])->name('payrolls.generate-all');
        Route::post('payrolls/bulk-mark-paid', [PayrollController::class, 'bulkMarkPaid'])->name('payrolls.bulk-mark-paid');
        Route::resource('payrolls', PayrollController::class);
        Route::post('payrolls/{id}/mark-paid', [PayrollController::class, 'markPaid'])->name('payrolls.mark-paid');
        Route::get('payrolls/{id}/payslip', [PayrollController::class, 'downloadPayslip'])->name('payrolls.payslip');

        // Employee Advances
        Route::get('advances/ajax-search-staffs', [EmployeeAdvanceController::class, 'ajaxSearchStaffs'])->name('advances.search-staffs');
        Route::get('advances', [EmployeeAdvanceController::class, 'index'])->name('advances.index');
        Route::post('advances', [EmployeeAdvanceController::class, 'store'])->name('advances.store');
        Route::put('advances/{id}', [EmployeeAdvanceController::class, 'update'])->name('advances.update');
        Route::delete('advances/{id}', [EmployeeAdvanceController::class, 'destroy'])->name('advances.destroy');
        Route::post('advances/{id}/status', [EmployeeAdvanceController::class, 'updateStatus'])->name('advances.update-status');

        // Items Master
        Route::resource('items', ItemController::class);
        Route::post('items/{id}/toggle-status', [ItemController::class, 'toggleStatus'])->name('items.toggle-status');

        // Laundry Orders
        Route::get('laundry-orders', [LaundryOrderController::class, 'index'])->name('laundry-orders.index');
        Route::post('laundry-orders', [LaundryOrderController::class, 'store'])->name('laundry-orders.store');
        Route::get('laundry-orders/{id}/edit', [LaundryOrderController::class, 'edit'])->name('laundry-orders.edit');
        Route::put('laundry-orders/{id}', [LaundryOrderController::class, 'update'])->name('laundry-orders.update');
        Route::delete('laundry-orders/{id}', [LaundryOrderController::class, 'destroy'])->name('laundry-orders.destroy');
        Route::get('laundry-orders/{id}/receive', [LaundryOrderController::class, 'getReceiveItems'])->name('laundry-orders.receive.get');
        Route::post('laundry-orders/{id}/receive', [LaundryOrderController::class, 'updateReceiveItems'])->name('laundry-orders.receive.update');
        Route::post('laundry-orders/mark-paid', [LaundryOrderController::class, 'markAsPaid'])->name('laundry-orders.mark-paid');

        // Expense Categories
        Route::get('expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
        Route::post('expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
        Route::get('expense-categories/{id}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-categories.edit');
        Route::put('expense-categories/{id}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
        Route::delete('expense-categories/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');

        // Expenses
        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('expenses/{id}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('expenses/{id}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('expenses/{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

        // Feedback
        Route::get('feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');

        // CRM
        Route::prefix('crm')->name('crm.')->group(function () {
            Route::get('sms', [SmsLogController::class, 'index'])->name('sms.index');
            Route::post('sms/send', [SmsLogController::class, 'send'])->name('sms.send');
            Route::get('whatsapp', [WhatsappLogController::class, 'index'])->name('whatsapp.index');
            Route::post('whatsapp/send', [WhatsappLogController::class, 'send'])->name('whatsapp.send');
            Route::get('email', [EmailLogController::class, 'index'])->name('email.index');
            Route::post('email/send', [EmailLogController::class, 'send'])->name('email.send');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('staff', [ReportController::class, 'staff'])->name('staff');
            Route::get('expenses', [ReportController::class, 'expenses'])->name('expenses');
            Route::get('laundry', [ReportController::class, 'laundry'])->name('laundry');
            Route::get('store-ledger', [ReportController::class, 'storeLedger'])->name('store-ledger');
            Route::get('package-usage', [ReportController::class, 'packageUsage'])->name('package-usage');
            Route::get('outstanding', [ReportController::class, 'outstanding'])->name('outstanding');
            Route::get('export-sales-pdf', [ReportController::class, 'exportSalesPdf'])->name('export.sales.pdf');
            Route::get('export-sales-excel', [ReportController::class, 'exportSalesExcel'])->name('export.sales.excel');
            Route::get('export-store-ledger-pdf', [ReportController::class, 'exportStoreLedgerPdf'])->name('export.store-ledger.pdf');
            Route::get('export-store-ledger-excel', [ReportController::class, 'exportStoreLedgerExcel'])->name('export.store-ledger.excel');
        });

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

// Server Migration Route
Route::get('/run-migration', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #28a745;'>✓ Database Migration Completed Successfully</h2>"
             . "<pre style='background: #1e1e1e; color: #00ff00; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($output ?: 'Nothing to migrate. Database is up to date.')
             . "</pre>"
             . "</div>";
    } catch (\Exception $e) {
        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #dc3545;'>✗ Migration Failed</h2>"
             . "<pre style='background: #1e1e1e; color: #ff5555; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($e->getMessage())
             . "</pre>"
             . "</div>";
    }
});

// Server Seeder Route
Route::get('/run-seeder', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'RolePermissionSeeder',
            '--force' => true,
        ]);
        $output = Artisan::output();
        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #28a745;'>✓ Role & Permission Seeder Completed Successfully</h2>"
             . "<pre style='background: #1e1e1e; color: #00ff00; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($output ?: 'RolePermissionSeeder executed successfully.')
             . "</pre>"
             . "</div>";
    } catch (\Exception $e) {
        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #dc3545;'>✗ Seeder Failed</h2>"
             . "<pre style='background: #1e1e1e; color: #ff5555; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($e->getMessage())
             . "</pre>"
             . "</div>";
    }
});

// Service Seeder Route (uploads categories & services to server)
Route::get('/run-service-seeder', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'ServiceSeeder',
            '--force' => true,
        ]);
        $output = Artisan::output();

        $categoryCount = \App\Models\ServiceCategory::count();
        $serviceCount = \App\Models\Service::count();

        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #28a745;'>✓ Service Seeder Completed Successfully</h2>"
             . "<p style='font-size: 16px; color: #555;'>Categories: <strong>{$categoryCount}</strong> | Services: <strong>{$serviceCount}</strong></p>"
             . "<pre style='background: #1e1e1e; color: #00ff00; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($output ?: 'ServiceSeeder executed successfully.')
             . "</pre>"
             . "</div>";
    } catch (\Exception $e) {
        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #dc3545;'>✗ Service Seeder Failed</h2>"
             . "<pre style='background: #1e1e1e; color: #ff5555; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($e->getMessage())
             . "</pre>"
             . "</div>";
    }
});

// Staff Seeder Route
Route::get('/run-staff-seeder', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'StaffSeeder',
            '--force' => true,
        ]);
        $output = Artisan::output();

        $staffCount = \App\Models\Staff::count();

        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #28a745;'>✓ Staff Seeder Completed Successfully</h2>"
             . "<p style='font-size: 16px; color: #555;'>Total Staff: <strong>{$staffCount}</strong></p>"
             . "<pre style='background: #1e1e1e; color: #00ff00; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($output ?: 'StaffSeeder executed successfully.')
             . "</pre>"
             . "</div>";
    } catch (\Exception $e) {
        return "<div style='font-family: sans-serif; padding: 20px; max-width: 800px; margin: 40px auto; text-align: center;'>"
             . "<h2 style='color: #dc3545;'>✗ Staff Seeder Failed</h2>"
             . "<pre style='background: #1e1e1e; color: #ff5555; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; text-align: left; white-space: pre-wrap;'>"
             . htmlspecialchars($e->getMessage())
             . "</pre>"
             . "</div>";
    }
});


/*
|--------------------------------------------------------------------------
| Cache & View Clearing Utility Routes
|--------------------------------------------------------------------------
*/
Route::get('/clear-cache', function () {
    try {
        Artisan::call('cache:clear');
        $output = Artisan::output();
        return response("<pre style='background:#0f172a;color:#38bdf8;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;line-height:1.6;'><b>✅ Application Cache Cleared Successfully!</b><br><br>" . e($output ?: 'Cache cleared.') . "</pre>");
    } catch (\Throwable $e) {
        return response("<pre style='background:#0f172a;color:#f87171;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;'>❌ Error clearing cache: " . e($e->getMessage()) . "</pre>", 500);
    }
})->name('clear.cache');

Route::get('/clear-view', function () {
    try {
        Artisan::call('view:clear');
        $output = Artisan::output();
        return response("<pre style='background:#0f172a;color:#38bdf8;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;line-height:1.6;'><b>✅ View Cache Cleared Successfully!</b><br><br>" . e($output ?: 'Compiled views cleared.') . "</pre>");
    } catch (\Throwable $e) {
        return response("<pre style='background:#0f172a;color:#f87171;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;'>❌ Error clearing views: " . e($e->getMessage()) . "</pre>", 500);
    }
})->name('clear.view');

Route::get('/clear-config', function () {
    try {
        Artisan::call('config:clear');
        $output = Artisan::output();
        return response("<pre style='background:#0f172a;color:#38bdf8;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;line-height:1.6;'><b>✅ Configuration Cache Cleared Successfully!</b><br><br>" . e($output ?: 'Configuration cache cleared.') . "</pre>");
    } catch (\Throwable $e) {
        return response("<pre style='background:#0f172a;color:#f87171;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;'>❌ Error clearing config cache: " . e($e->getMessage()) . "</pre>", 500);
    }
})->name('clear.config');

Route::get('/clear-route', function () {
    try {
        Artisan::call('route:clear');
        $output = Artisan::output();
        return response("<pre style='background:#0f172a;color:#38bdf8;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;line-height:1.6;'><b>✅ Route Cache Cleared Successfully!</b><br><br>" . e($output ?: 'Route cache cleared.') . "</pre>");
    } catch (\Throwable $e) {
        return response("<pre style='background:#0f172a;color:#f87171;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;'>❌ Error clearing route cache: " . e($e->getMessage()) . "</pre>", 500);
    }
})->name('clear.route');

Route::get('/clear-all', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');
        return response("<pre style='background:#0f172a;color:#4ade80;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;line-height:1.6;'><b>🚀 ALL Server Caches Cleared Successfully!</b><br><br>• Application Cache: Cleared<br>• View Cache: Cleared<br>• Config Cache: Cleared<br>• Route Cache: Cleared<br>• Optimized Compiled Files: Cleared</pre>");
    } catch (\Throwable $e) {
        return response("<pre style='background:#0f172a;color:#f87171;padding:25px;border-radius:10px;font-family:monospace;font-size:15px;'>❌ Error clearing all caches: " . e($e->getMessage()) . "</pre>", 500);
    }
})->name('clear.all');

