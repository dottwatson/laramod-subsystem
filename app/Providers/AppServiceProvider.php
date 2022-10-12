<?php

namespace App\Providers;

use Dottwatson\Fpdo\DataParser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use App\Models\JobDone;

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
        //
    }
}
