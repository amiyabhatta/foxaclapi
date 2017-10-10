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
use Fox\Common\Foxvalidation;

class AlertContainer extends Base implements AlertContract
{

    use Foxvalidation;

    protected $userTransformer;

    public function __construct($usertrade)
    {
        $this->usertrade = $usertrade;
    }

    /**
     * save user trade data
     * 
     * @param type $request
     * @return type json
     */
    public function saveuserTrades($request)
    {
        $servermgrId = common::serverManagerId();
        $res = $this->usertrade->saveTradeValue($request, $servermgrId['server_name'], $servermgrId['login']);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }
        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_alert_save')),
                    'status_code' => 200
                        //'last_insert_id' => $res,
        ]);
    }

    /**
     * update user trade data by passing volume and isUpdate
     * @param type $request
     * @param type $login
     * @return type json
     */
    public function updateuserTrades($request, $login)
    {
        $servermgrId = common::serverManagerId();
        //Check login is valid or not
        $checkLogin = $this->usertrade->checkTradelogin($login, $servermgrId['server_name'], $servermgrId['login']);

        if (!$checkLogin) {
            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.login_not_found'),
                        'status_code' => 404
            ]);
        }

        $res = $this->usertrade->updateTradeValue($request, $servermgrId['server_name'], $servermgrId['login'], $login);



        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        } else if ($res === 'already_asign') {
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

    /**
     * Delete user trade by login
     * 
     * @param type $login
     * @return type json
     */
    public function deleteuserTrades($login)
    {
        $servermgrId = common::serverManagerId();

        //Check login is valid or not
        $checkLogin = $this->usertrade->checkTradelogin($login, $servermgrId['server_name'], $servermgrId['login']);

        if (!$checkLogin) {
            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.login_not_found'),
                        'status_code' => 404
            ]);
        }

        $res = $this->usertrade->deleteTradeValue($servermgrId['server_name'], $servermgrId['login'], $login);

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

    /**
     * get user trade by Id Note : id is optional
     * 
     * 
     * @param type $id
     * @return type json
     */
    public function getuserTrades($tradeId)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->usertrade->getTradeValue($servermgrId['server_name'], $servermgrId['login'], $tradeId);

        return response()->json($res);
    }
    /**
     * 
     * Get login for a particular login and server
     * @return type array
     */
    public function getLogin()
    {
        $servermgrId = common::serverManagerId();
        $res = $this->usertrade->getLogin($servermgrId['server_name'], $servermgrId['login']);
        return $res;
    }

}
