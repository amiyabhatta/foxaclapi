<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\AuditlogContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\Auditlog;



class AuditlogServiceProvider extends ServiceProvider
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
        $auditlog = new Auditlog;
        
        App::bind('Fox\Services\Contracts\AuditlogContract', function() use($auditlog) {
            return new AuditlogContainer($auditlog);
        });
    }

}
