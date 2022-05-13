<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*',function($view) {
            // Thông tin admin
            $info = Admin::where('admin_id', Session::get('admin_id'))->first();

            //Total
            $app_product = Product::all()->count();
            $app_customer = Customer::all()->count();
            $app_order = Order::all()->count();
            $app_comment = Comment::all()->count();

            $view->with(compact('info','app_product','app_customer','app_order','app_comment'));
        });
    }
}
