<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\WlEmailAlertContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;

class WlEmailAlertContainer extends Base implements WlEmailAlertContract
{
    
    public function __construct($lasttradeemailalert)
    {
        $this->lasttradeemailalert = $lasttradeemailalert;
    }
    
     /**
     * Save Last trade email alert data
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function saveLastTradeWlEmailAlert($request)
    {
        
        //Validation
        $validate = Validator::make($request->all(), [
                    "ticket" => 'required|check_valid_ticket|unique:lasttrade_whitelabels_emails_alert',
                    "whitelabel" => 'required|check_valid_whitelabel'
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
        $res = $this->lasttradeemailalert->saveLastTradeWlEmailAlert($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.email_alert_added')),
                    'status_code' => 200
        ]);
    }
    
     /**
     * Get record by some parameter
     * 
     * 
     * @param type $request
     * @return type json 
     */
    public function getLastTradeWlEmailAlert($request)
    {

        //Validation
        $validate = Validator::make($request->all(), [
                    "ticket" => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
        return $this->lasttradeemailalert->getLastTradeWlEmailAlert($request);
    }
    
}
