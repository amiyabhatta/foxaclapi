<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\AlertContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\Usertrade;
use Fox\Models\lasttrade_whitelabels;
use Fox\Models\ReportGroup;
use Fox\Models\ReportGroupUser;
use Fox\Models\auditlog;
use Fox\Models\lasttrade_whitelabels_emails_alert;

class AlertServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //
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
        $usertrade = new Usertrade;
        $lasttrade = new lasttrade_whitelabels;
        $reportgroup = new ReportGroup;
        $reportgroupuser = new ReportGroupUser;
        $auditlog = new auditlog;
        $lasttradeemailalert = new lasttrade_whitelabels_emails_alert;
        
        App::bind('Fox\Services\Contracts\AlertContract', function() use($usertrade, $lasttrade, $reportgroup, $reportgroupuser, $auditlog, $lasttradeemailalert) {
            return new AlertContainer($usertrade, $lasttrade, $reportgroup, $reportgroupuser, $auditlog, $lasttradeemailalert);
        });
    }

}
