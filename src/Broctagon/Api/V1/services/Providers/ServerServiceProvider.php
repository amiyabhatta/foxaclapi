<?php

namespace Fox\Services\Providers;


use Fox\Services\Containers\ServerContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\Serverlist;
use Fox\Transformers\serverTransformer;

class ServerServiceProvider extends ServiceProvider {

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
        $serverTransformer = new serverTransformer;
        $server = new Serverlist;
        App::bind('Fox\Services\Contracts\ServerContract', function() use($serverTransformer, $server) {
            return new ServerContainer($serverTransformer, $server);
        });
    }

}
