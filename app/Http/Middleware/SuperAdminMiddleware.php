<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            Log::info('SuperAdminMiddleware: User not authenticated');
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is either an admin or super admin
        $isAdmin = Admin::where('user_id', $user->id)->exists();
        $isSuperAdmin = SuperAdmin::where('user_id', $user->id)->exists();

        Log::info('SuperAdminMiddleware check', [
            'user_id' => $user->id,
            'is_admin' => $isAdmin,
            'is_super_admin' => $isSuperAdmin,
            'route' => $request->route()->getName()
        ]);

        if (!$isAdmin && !$isSuperAdmin) {
            Log::warning('SuperAdminMiddleware: Access denied for user', ['user_id' => $user->id]);
            return redirect()->route('home.index')->with('error', 'Unauthorized access.');
        }

        // List of routes that only super admins can access
        $superAdminOnlyRoutes = [
            'admin.revenue',
            // Add other super admin only routes here if needed
        ];

        // If accessing a super admin only route and not a super admin
        if (in_array($request->route()->getName(), $superAdminOnlyRoutes) && !$isSuperAdmin) {
            Log::warning('SuperAdminMiddleware: Restricted access denied for non-super-admin', ['user_id' => $user->id]);
            return redirect()->route('admin.dashboard')->with('error', 'Only super administrators can access this feature.');
        }

        return $next($request);
    }
}
