<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        // Check if account status is inactive or suspended
        if (isset($user->status) && in_array($user->status, ['inactive', 'suspended'])) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
