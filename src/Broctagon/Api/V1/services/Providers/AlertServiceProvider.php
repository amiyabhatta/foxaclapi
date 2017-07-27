<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\AlertContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\Usertrade;

class AlertServiceProvider extends ServiceProvider {

    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        /**
         * Service Layer
         */
        $usertrade = new Usertrade;
        App::bind('Fox\Services\Contracts\AlertContract', function() use($usertrade) {            
            return new AlertContainer($usertrade);
        });
    }

}
