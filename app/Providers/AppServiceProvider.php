<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Cart;
use App\Models\Wishlist;
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
            $categories = Category::orderBy('name')->get();
            $cartCount = auth()->check() ? Cart::where('user_id', auth()->id())->sum('quantity') : 0;
            $wishlistCount = auth()->check() ? Wishlist::where('user_id', auth()->id())->count() : 0;

            $view->with(compact('categories', 'cartCount', 'wishlistCount'));
        });
    }
}
