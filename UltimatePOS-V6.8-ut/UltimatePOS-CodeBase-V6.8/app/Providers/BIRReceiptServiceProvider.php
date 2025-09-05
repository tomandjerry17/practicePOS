<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class BIRReceiptServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register BIR Receipt Plugin views
        View::addNamespace('birreceiptplugin', base_path('Modules/BIRReceiptPlugin/Resources/views'));
    }
}
