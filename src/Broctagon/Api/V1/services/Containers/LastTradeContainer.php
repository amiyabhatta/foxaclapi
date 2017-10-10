<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\LastTradeContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;

class LastTradeContainer extends Base implements LastTradeContract
{

    public function __construct($lasttrade)
    {
        $this->lasttrade = $lasttrade;
    }

    /**
     * Get Last trade list by Id
     * 
     * 
     * @param type $id
     * @return type json
     */
    public function getLastTradeList($lastTradeId)
    {   
        $servermgrId = common::serverManagerId();
        
        $res = $this->lasttrade->getlatsTradeList($servermgrId['server_name'], $lastTradeId);

        return response()->json($res);
    }

    /**
     * Update last trade list by Id
     * 
     * @param type $id
     * @param type $request
     * @return type json
     */
    public function updateLastTradeList($lastTradeId, $request)
    {
        $validate = Validator::make($request->all(), [
                    "botime" => 'required|numeric',
                    "fxtime" => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
        $servermgrId = common::serverManagerId();

        $res = $this->lasttrade->updatelatsTrade($servermgrId['server_name'], $lastTradeId, $request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_update')),
                    'status_code' => 200
        ]);
    }

    /**
     * Create white label
     * 
     * @param type $request
     * @return type json
     */
    public function createWhiteLabel($request)
    {
        
            $res = $this->lasttrade->createWl($request);

            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(200)->respond([
                        'message' => (trans('user.wl_created')),
                        'status_code' => 200
            ]);
       
    }

    /**
     * update whitelabel by Id
     * 
     * 
     * @param type $request
     * @param type $id
     * @return type json
     */
    public function updateWhiteLabel($request, $wlId)
    {
        
        $res = $this->lasttrade->updateWl($request, $wlId);

            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(200)->respond([
                        'message' => (trans('user.wl_update')),
                        'status_code' => 200
            ]);
        
    }

    /**
     * Delete whitelable by id
     * 
     * 
     * @param type $id
     * @return type json
     */
    public function deleteWhiteLabel($wlId)
    {
        

            $res = $this->lasttrade->deleteWl($wlId);

            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(200)->respond([
                        'message' => (trans('user.wl_delete')),
                        'status_code' => 200
            ]);
       
    }

    /**
     * Get white label by id
     * 
     * 
     * @param type $id
     * @return type json
     */
    public function getWhiteLabel($wlId)
    {

        $servermgrId = common::serverManagerId();

        return $this->lasttrade->getWhiteLabelList($servermgrId['server_name'], $wlId);
    }

}
