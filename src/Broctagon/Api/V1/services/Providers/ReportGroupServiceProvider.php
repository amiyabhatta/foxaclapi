<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\ReportgroupContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\ReportGroup;
use Fox\Models\ReportGroupUser;



class ReportgroupServiceProvider extends ServiceProvider
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
        $reportgroup = new ReportGroup;
        $reportgroupuser = new ReportGroupUser;
        
        App::bind('Fox\Services\Contracts\ReportgroupContract', function() use($reportgroup, $reportgroupuser) {
            return new ReportgroupContainer($reportgroup, $reportgroupuser);
        });
    }

}
