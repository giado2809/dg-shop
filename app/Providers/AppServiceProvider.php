<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            $count = 0;

            if (Auth::check()) {
                $userId = Auth::id();
                $count = Cart::where('user_id', $userId)->count();
            }

            $view->with('cartCount', $count);
        });

        View::share('categories', Category::withCount('products')->get());
    }
}