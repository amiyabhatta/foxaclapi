<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\TabselectContainer;
use Illuminate\Support\ServiceProvider;
use Fox\Models\TabSelected;
use Illuminate\Support\Facades\App;

class TabselectServiceProvider extends ServiceProvider {

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
        $tabselect = new TabSelected;
        
        App::bind('Fox\Services\Contracts\TabselectContract', function() use($tabselect) {
            return new TabselectContainer($tabselect);
        });
    }

}
