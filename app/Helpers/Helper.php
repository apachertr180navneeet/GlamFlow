<?php

use App\Models\ActivityLog;
use App\Models\Setting;

if (!function_exists('logActivity')) {
    function logActivity($action, $module, $description = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}

if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status)
    {
        $colors = [
            'active' => 'success',
            'inactive' => 'secondary',
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'paid' => 'success',
            'unpaid' => 'danger',
            'partial' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'half_day' => 'info',
        ];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$status}</span>";
    }
}
