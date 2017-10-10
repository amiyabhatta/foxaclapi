<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\LastTradeContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\LastTradeWhitelabels;




class LastTradeServiceProvider extends ServiceProvider
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
        $lasttrade = new LastTradeWhitelabels;
        
        App::bind('Fox\Services\Contracts\LastTradeContract', function() use($lasttrade) {
            return new LastTradeContainer($lasttrade);
        });
    }

}
