<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use App\Models\SuperAdmin;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user is either an admin or super admin
        $isAdmin = Admin::where('user_id', $user->id)->exists();
        $isSuperAdmin = SuperAdmin::where('user_id', $user->id)->exists();

        if (!$isAdmin && !$isSuperAdmin) {
            return redirect()->route('home.index')->with('error', 'Unauthorized access.');
        }

        // If accessing revenue insights, only super admin is allowed
        if ($request->route()->getName() === 'admin.revenue' && !$isSuperAdmin) {
            return redirect()->route('admin.dashboard')->with('error', 'Only super administrators can access revenue insights.');
        }

        return $next($request);
    }
}
