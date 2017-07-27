<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\AlertContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Fox\Common\Common;

class AlertContainer extends Base implements AlertContract
{

    protected $userTransformer;

    public function __construct($usertrade)
    {
        $this->usertrade = $usertrade;
    }

    /**
     * Get Users Trades.
     * Paginator adapter is used for pagination.     * 
     * @return Collection
     */
    public function saveuserTrades($request)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $res = $this->usertrade->saveTradeValue($request, $server_name, $login_id);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }
        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_alert_save')),
                    'status_code' => 200
        ]);
    }

    public function updateuserTrades($request, $id)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $res = $this->usertrade->updateTradeValue($request, $server_name, $login_id, $id);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }
        else if ($res === 'already_asign') {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.trade_alert_already_assign'),
                        'status_code' => 500
            ]);
        }
        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_alert_update')),
                    'status_code' => 200
        ]);
    }

    public function deleteuserTrades($id)
    {

        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;

        $res = $this->usertrade->deleteTradeValue($server_name, $login_id, $id);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_alert_delete')),
                    'status_code' => 200
        ]);
    }

    public function getuserTrades($id)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;

        $res = $this->usertrade->getTradeValue($server_name, $login_id, $id);
        
        return response()->json($res);
    }

}
