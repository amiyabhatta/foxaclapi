<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\RoleContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
//use Fox\Transformers\UserTransformer;
use Fox\Models\Role;
use Fox\Transformers\RoleTransformer;



class RoleServiceProvider extends ServiceProvider {

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
        
       
        $roleTransformer = new RoleTransformer;             
        $role = new Role;        
        App::bind('Fox\Services\Contracts\RoleContract', function() use($role, $roleTransformer) {            
            return new RoleContainer($role, $roleTransformer);
        });
    }

}
