<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // Ensure this path matches your Admin model

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Admin::where('user_id', Auth::id())->exists()) {
            return $next($request);
        }

        return redirect('/'); // Redirect or return a response as needed
    }
}


