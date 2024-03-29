<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\UserContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Transformers\UserTransformer;
use Fox\Models\User;
use Fox\Models\glb_alert_setting_OM;
use Fox\Models\Bo_alert_setting;
use Fox\Models\TabSelected;

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
        $global_setting = new glb_alert_setting_OM;
        $bo_alert = new Bo_alert_setting;
        $tabselect = new TabSelected;
        
        App::bind('Fox\Services\Contracts\UserContract', function() use($userTransformer, $user, $global_setting, $bo_alert, $tabselect) {
            return new UserContainer($userTransformer, $user, $global_setting, $bo_alert, $tabselect);
        });
    }

}
