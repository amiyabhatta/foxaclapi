<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\MailsettingContract;
use Illuminate\Http\Request;

class MailsettingController extends Controller
{
    public function __construct(MailsettingContract $mailSettingContainer)
    {
        $this->mailSettingContainer = $mailSettingContainer;
    }
    
    /**
     * save data for mail for different server
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function saveMailSetting(Request $request){
       return $this->mailSettingContainer->saveMailSetting($request);
    }
}
