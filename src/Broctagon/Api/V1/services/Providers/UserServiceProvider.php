<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\UserContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Transformers\UserTransformer;
use Fox\Models\User;


class UserServiceProvider extends ServiceProvider {

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
        $userTransformer = new UserTransformer;
        $user = new User;
        
        App::bind('Fox\Services\Contracts\UserContract', function() use($userTransformer, $user) {
            return new UserContainer($userTransformer, $user);
        });
    }

}
