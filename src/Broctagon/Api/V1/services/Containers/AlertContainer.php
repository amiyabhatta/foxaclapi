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

    public function __construct($usertrade, $lasttrade, $reportgroup, $reportgroupuser, $auditlog)
    {
        $this->usertrade = $usertrade;
        $this->lasttrade = $lasttrade;
        $this->reportgroup = $reportgroup;
        $this->reportgroupuser = $reportgroupuser;
        $this->auditlog = $auditlog;
    }

    /**
     * Get Users Trades.
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
                    'status_code' => 200,
                    'last_insert_id' => $res,
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

    /**
     * Get Last Trades.
     * Paginator adapter is used for pagination.     * 
     * @return Collection
     */
    public function getLastTradeList($id)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->lasttrade->getlatsTradeList($servermgrId['server_name'], $servermgrId['login'], $id);

        return response()->json($res);
    }

    public function updateLastTradeList($id, $request)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->lasttrade->updatelatsTrade($servermgrId['server_name'], $servermgrId['login'], $id, $request);

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

    public function createWhiteLabel($request)
    {
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

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
        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }

    public function updateWhiteLabel($request, $id)
    {
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->lasttrade->updateWl($request, $id);

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
        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }

    public function deleteWhiteLabel($id)
    {
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->lasttrade->deleteWl($id);

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
        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }

    /*
     * Save Report and group
     */

    public function saveReportGroup($request)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->reportgroup->saveReportGroup($servermgrId['server_name'], $servermgrId['login'], $request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_group_created')),
                    'status_code' => 200
        ]);
    }

    public function updateReportGroup($request)
    {

        $servermgrId = common::serverManagerId();

        $res = $this->reportgroup->updateReportGroup($servermgrId['server_name'], $servermgrId['login'], $request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_group_updated')),
                    'status_code' => 200
        ]);
    }

    public function getTradeList($id)
    {

        $servermgrId = common::serverManagerId();

        $res = $this->reportgroup->getTradeGrpList($servermgrId['server_name'], $servermgrId['login'], $id);

        return $res;
    }

    public function deleteTradeList($request)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->reportgroup->deleteTradeGrpList($servermgrId['server_name'], $servermgrId['login'], $request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.trade_group_deleted')),
                    'status_code' => 200
        ]);
    }

    /*
     * Audit Log
     */

    public function saveAuditLog($request)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->auditlog->saveAuditLog($servermgrId['server_name'], $request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.audit_log_created')),
                    'status_code' => 200
        ]);
    }

    public function getAuditLog($request)
    {

        $servermgrId = common::serverManagerId();



        if (empty($request->input('start_date') && $request->input('end_date'))) {
            if (empty($request->input('start_date'))) {
                return $this->setStatusCode(200)->respond([
                            'message' => (trans('user.audit_start_date')),
                ]);
            }
            if (empty($request->input('end_date'))) {
                return $this->setStatusCode(200)->respond([
                            'message' => (trans('user.audit_end_date')),
                ]);
            }
        }
        //Compaire date filed
      
        $res = $this->auditlog->getAuditLog($servermgrId['server_name'], $request);
        return $res;
    }

}
