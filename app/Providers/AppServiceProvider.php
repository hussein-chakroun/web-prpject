<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Cart; 
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('cartItemCount', function() { 
            $cart = Cart::where('user_id', Auth::id())->first(); 
            return $cart ? $cart->cartItems()->count() : 0; });
    }
}
