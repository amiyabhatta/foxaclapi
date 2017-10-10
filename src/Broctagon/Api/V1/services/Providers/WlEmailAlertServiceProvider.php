<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\WlEmailAlertContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\LasttradeWlEmailAlert;



class WlEmailAlertServiceProvider extends ServiceProvider
{

    public function boot()
    {
       
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Service Layer
         */
        $lasttradeemailalert = new LasttradeWlEmailAlert;
        
        App::bind('Fox\Services\Contracts\WlEmailAlertContract', function() use($lasttradeemailalert) {
            return new WlEmailAlertContainer($lasttradeemailalert);
        });
    }

}
