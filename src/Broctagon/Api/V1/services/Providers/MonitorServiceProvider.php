<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\MonitorContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\GlbAlertSettingOm;
use Fox\Models\BoalertSetting;

class MonitorServiceProvider extends ServiceProvider {

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
        $globalSetting = new GlbAlertSettingOm;
        $boAlert = new BoalertSetting;
        App::bind('Fox\Services\Contracts\MonitorContract', function() use($globalSetting, $boAlert) {
            return new MonitorContainer($globalSetting, $boAlert);
        });
    }

}
