<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\GatewayContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Transformers\GatewayTransformer;
use Fox\Models\User;
use Fox\Models\Mt4gateway;

class GatewayServiceProvider extends ServiceProvider {

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
        $gatewayTransformer = new GatewayTransformer;
        $gateway = new Mt4gateway;
        App::bind('Fox\Services\Contracts\GatewayContract', function() use($gatewayTransformer, $gateway) {
            return new GatewayContainer($gatewayTransformer, $gateway);
        });
    }

}
