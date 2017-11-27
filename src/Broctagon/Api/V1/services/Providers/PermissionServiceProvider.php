<?php

namespace Fox\Services\Providers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
//use Fox\Transformers\UserTransformer;
use Fox\Models\Permissions;
use Fox\Services\Containers\PermissionContainer;
use Fox\Transformers\permissionTransformer;
use League\Fractal\Manager;



class PermissionServiceProvider extends ServiceProvider {

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
        $permissionTransformer = new permissionTransformer;             
        $permission = new Permissions; 
        $manager = new Manager;
        App::bind('Fox\Services\Contracts\PermissionContract', function() use($permission, $permissionTransformer, $manager) {                        
            return new PermissionContainer($permission, $permissionTransformer, $manager);
        });
    }

}
