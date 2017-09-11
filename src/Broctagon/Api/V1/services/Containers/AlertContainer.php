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

class AlertContainer extends Base implements AlertContract {

    use Foxvalidation;

    protected $userTransformer;

    public function __construct($usertrade, $lasttrade, $reportgroup, $reportgroupuser, $auditlog, $lasttradeemailalert, $mailsetting, $tradealertdiscard) {
        $this->usertrade = $usertrade;
        $this->lasttrade = $lasttrade;
        $this->reportgroup = $reportgroup;
        $this->reportgroupuser = $reportgroupuser;
        $this->auditlog = $auditlog;
        $this->lasttradeemailalert = $lasttradeemailalert;
        $this->mailsetting = $mailsetting;
        $this->tradealert = $tradealertdiscard;
    }

    /**
     * Get Users Trades.
     */
    public function saveuserTrades($request) {
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
                        //'last_insert_id' => $res,
        ]);
    }

    public function updateuserTrades($request, $login) {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;

        //Check login is valid or not
        $checkLogin = $this->usertrade->checkTradelogin($login, $server_name, $login_id);

        if (!$checkLogin) {
            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.login_not_found'),
                        'status_code' => 404
            ]);
        }

        $res = $this->usertrade->updateTradeValue($request, $server_name, $login_id, $login);



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

    public function deleteuserTrades($login) {

        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;

        //Check login is valid or not
        $checkLogin = $this->usertrade->checkTradelogin($login, $server_name, $login_id);

        if (!$checkLogin) {
            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.login_not_found'),
                        'status_code' => 404
            ]);
        }

        $res = $this->usertrade->deleteTradeValue($server_name, $login_id, $login);

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

    public function getuserTrades($id) {
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
    public function getLastTradeList($id) {
        $servermgrId = common::serverManagerId();

        $res = $this->lasttrade->getlatsTradeList($servermgrId['server_name'], $servermgrId['login'], $id);

        return response()->json($res);
    }

    public function updateLastTradeList($id, $request) {
        $validate = Validator::make($request->all(), [
                    "botime" => 'required|numeric',
                    "fxtime" => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
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

    public function createWhiteLabel($request) {
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

    public function updateWhiteLabel($request, $id) {
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

    public function deleteWhiteLabel($id) {
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

    public function saveReportGroup($request) {
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

    public function updateReportGroup($request) {

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

    public function getTradeList($id) {

        $servermgrId = common::serverManagerId();

        $res = $this->reportgroup->getTradeGrpList($servermgrId['server_name'], $servermgrId['login'], $id);

        return $res;
    }

    public function deleteTradeList($request) {

        //Calling trait for validation
        $validate = $this->deleteReportGroupValidation($request);

        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        //check id is availbe or not
        $checkgroupId = $this->reportgroup->checkGroupid($servermgrId['server_name'], $servermgrId['login'], $request);

        if (!$checkgroupId) {
            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.id_not_found'),
                        'status_code' => 404
            ]);
        }

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

    public function saveAuditLog($request) {
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

    public function getAuditLog($request) {

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

    /*
     * Get white label list
     */

    public function getWhiteLabel($id) {

        $servermgrId = common::serverManagerId();

        return $res = $this->lasttrade->getWhiteLabelList($servermgrId['server_name'], $id);
    }

    /*
     * Get White labellast trade 
     */

    public function getLastTradeWlEmailAlert($request) {
        
        //Validation
        $validate = Validator::make($request->all(), [
                    "ticket" => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
        return $res = $this->lasttradeemailalert->getLastTradeWlEmailAlert($request);
    }

    public function saveLastTradeWlEmailAlert($request) {

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

    /*
     * Save mail setting
     */

    public function saveMailSetting($request) {

        //validation
        $servermgrId = common::serverManagerId();
        $res = $this->mailsetting->saveMailSetting($request, $servermgrId['server_name'], $servermgrId['login']);
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

    /*
     * Save Tarde Alert Discrad
     */

    public function saveTradeAlertDiscrad($request) {

        //validation
        //Validation
        $validate = Validator::make($request->all(), [
                    "ticket" => 'required|valid_ticket|unique:trade_alert_discard',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        $res = $this->tradealert->saveTardeAlertDiscrd($request, $servermgrId['login']);
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

    public function getTradeAlertDiscrad($request) {

        //validation
        //Validation
        $validate = Validator::make($request->all(), [
                    "addedon" => 'required',
        ]);

        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        $res = $this->tradealert->getTardeAlertDiscrd($request, $servermgrId['login']);
        return $res;
    }

    /*
     * get login for logim
     */

    public function getLogin() {
        $servermgrId = common::serverManagerId();
        $res = $this->usertrade->getLogin($servermgrId['server_name'], $servermgrId['login']);
        return $res;
    }

}
