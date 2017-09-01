<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use DB;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        
        $this->app['validator']->extend('only_numeric', function ($attribute, $value, $parameters, $validator) {
            $login = explode(',', rtrim($value, ','));
            foreach ($login as $chkNumericLogin) {
                if (!is_numeric($chkNumericLogin) || $chkNumericLogin <= 0) {
                    return false;
                }
            }
            return true;
        });

        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
