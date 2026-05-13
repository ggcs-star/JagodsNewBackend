<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Composers\BackendMenuComposer;

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
        Schema::defaultStringLength(191);


        if (file_exists(storage_path('installed'))) {

            view()->composer('*', function ($view) {
                if (Auth::check()):
                    $view->with('backendMenus', BackendMenuComposer::backendMenu());
                    $view->with('backendLanguage', BackendMenuComposer::backendLanguage());
                endif;
            });
            
            View::composer('frontend.partials._footer', 'App\Http\Composers\FrontendFooterComposer');
            View::composer('frontend.partials._nav', 'App\Http\Composers\FrontendFooterComposer');
        }



        Paginator::useBootstrap();
    }
}
