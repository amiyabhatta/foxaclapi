<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\TradeAlertDiscardContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;

class TradeAlertDiscardContainer extends Base implements TradeAlertDiscardContract
{
    
    public function __construct($tradealertDiscard)
    {
        $this->tradealertDiscard = $tradealertDiscard;
    }
    
    /**
     * Save trade alert discard data
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function saveTradeAlertDiscrad($request)
    {
        //Validation
        $validate = Validator::make($request->all(), [
                    "ticket" => 'required|valid_ticket|unique:trade_alert_discard',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        $res = $this->tradealertDiscard->saveTardeAlertDiscrd($request, $servermgrId['login']);
        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_alert_added')),
                    'status_code' => 200
        ]);
    }

    /**
     * Get Trade alert discard data
     * 
     * @param type $request
     * @return type json
     */
    public function getTradeAlertDiscrad($request)
    {

        //validation
        //Validation
        $validate = Validator::make($request->all(), [
                    "addedon" => 'required',
        ]);

        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        $res = $this->tradealertDiscard->getTardeAlertDiscrd($request, $servermgrId['login']);
        return $res;
    }
     
}
