<?php

namespace Fox\Services\Providers;

use Fox\Services\Containers\MailsettingContainer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fox\Models\Mailsetting;



class MailsettingServiceProvider extends ServiceProvider
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
        $mailsetting = new Mailsetting;
        App::bind('Fox\Services\Contracts\MailsettingContract', function() use($mailsetting) {
            return new MailsettingContainer($mailsetting);
        });
    }

}
