<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use DB;
use Fox\Models\ReportGroup;

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

        //Check 
        $this->app['validator']->extend('check_id', function ($attribute, $value, $parameters, $validator) {
            $wl = ReportGroup::find($value);
            if(count($wl)){
               return true; 
            }
            return false;
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
