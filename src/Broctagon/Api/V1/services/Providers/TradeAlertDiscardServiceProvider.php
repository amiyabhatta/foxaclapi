<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\TradeAlertDiscardContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\TradeAlertDiscard;



class TradeAlertDiscardServiceProvider extends ServiceProvider
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
        $tradealertDiscard = new TradeAlertDiscard;
        App::bind('Fox\Services\Contracts\TradeAlertDiscardContract', function() use($tradealertDiscard) {
            return new TradeAlertDiscardContainer($tradealertDiscard);
        });
    }

}
