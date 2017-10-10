<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\MailsettingContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;

class MailsettingContainer extends Base implements MailsettingContract
{
    
    public function __construct($mailsetting)
    {
        $this->mailSetting = $mailsetting;
    }
    
    /**
     * Save mailsetting info for different server
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function saveMailSetting($request)
    {

        //validation
        $servermgrId = common::serverManagerId();
        $res = $this->mailSetting->saveMailSetting($request, $servermgrId['server_name'], $servermgrId['login']);
        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.mail_setting_added')),
                    'status_code' => 200
        ]);
    }
    
     
}
