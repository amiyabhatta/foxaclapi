<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\UserContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Fox\Common\Common;
use Fox\Models\Role;

class UserContainer extends Base implements UserContract
{

    protected $userTransformer;
    private $usermodel;
    private $roleModel;

    public function __construct($userTransformer, $user, $global_setting, $bo_alert)
    {
        $this->userTransformer = $userTransformer;
        $this->usermodel = $user;
        $this->globalSettingOm = $global_setting;
        $this->bolAlertSetting = $bo_alert;
    }

    /**
     * Get Users.
     * Paginator adapter is used for pagination.
     * @author Dibya lochan Nayak <dibyalochan.nayak@broctagon.com>
     * @return Collection
     */
    public function getUsers($id)
    {
        $limit = Input::get('limit', 20);

        $user = $this->usermodel->getAllUsers($limit, $id);

        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $user->appends($queryParams);

        $userAdapter = new IlluminatePaginatorAdapter($user);
        $resource = new Collection($user, $this->userTransformer);
        $resource->setPaginator($userAdapter);

        return $resource;
    }

    /**
     * Login
     *
     * @param type $request
     * @return type
     */
    public function login($request)
    {

        //$credentials = $request->only('email', 'password');
        $credentials = $request->only('manager_id', 'password');

        $custom_value = $request->only('server_name');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials, $custom_value)) {

                // return $this->setStatusCode(401)->respondWithError('Invalid Credentials');
                return $this->setStatusCode(200)->respond(['message' => trans('user.invalid_creds'),
                            'status_code' => 404]);
            }
        }
        catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token

            return $this->setStatusCode(200)->respond(['message' => trans('user.not_create_token'),
                        'status_code' => 500]);
        }

        $user = JWTAuth::authenticate($token);
        $server_name = $request->input('server_name');
        $server_details = $this->usermodel->getUserServerDetails($user->id, $server_name);
        $tab_details = $this->usermodel->getUserPermissionDetails($user->id);
        $gateway_details = $this->usermodel->getUserGatewayDetails($server_name);
        $db_detials = $this->usermodel->getDbDetails();
        $mail_setting = $this->usermodel->getMailSetting($user->id, $server_name);

        $token = encrypt($token);
        // all good so return the token
        return $this->setStatusCode(200)->respondWithToken(compact('token', 'server_details', 'tab_details', 'gateway_details', 'db_detials', 'mail_setting'));
    }

    public function createUser($request)
    {
        $res = $this->usermodel->addUser($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.created_sucess'),
                    'status_code' => 201
        ]);
    }

    public function updateUser($request)
    {
        $res = $this->usermodel->updateUser($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.update_sucess'),
                    'status_code' => 201
        ]);
    }

    public function deleteUser($request)
    {
        
        $res = $this->usermodel->deleteUser($request);

        if (!$res) {

            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.not_found'),
                        'status_code' => 404
            ]);
        }
        elseif ($res === 'error') {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.delete_sucess'),
                    'status_code' => 201
        ]);
    }

    public function assignRole($request)
    {

        $res = $this->usermodel->assignRoleToUser($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (($request->input('action')) ? trans('user.role_assign') : trans('user.role_assign_update')),
                    'status_code' => 200
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->respond(['status_code' => 401, 'message' => trans('user.logout')]);
    }

    public function Uilogin($request)
    {

        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {

                // return $this->setStatusCode(401)->respondWithError('Invalid Credentials');
                return $this->setStatusCode(200)->respond(['message' => trans('user.invalid_creds'),
                            'status_code' => 404]);
            }
        }
        catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token

            return $this->setStatusCode(200)->respond(['message' => trans('user.not_create_token'),
                        'status_code' => 500]);
        }

        $token = encrypt($token);
        // all good so return the token
        return $this->setStatusCode(200)->respondWithToken(compact('token'));
    }

    public function setGlobalAlertOm($request)
    {
        //get server name from token
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $res = $this->globalSettingOm->saveSetting($request, $server_name, $login_id);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.global_alert')),
                    'status_code' => 200
        ]);
    }

    public function getGlobalAlertOm()
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $getGloablSettingData = $this->globalSettingOm->getSetting($server_name, $login_id);

        $ret = [];

        foreach ($getGloablSettingData as $key => $dt) {
            $ret[$dt->alert_type]['volume_limit1'] = $dt->volume_limit1;
            $ret[$dt->alert_type]['volume_limit2'] = $dt->volume_limit2;
            $ret[$dt->alert_type]['avg_volume_limit1'] = $dt->avg_volume_limit1;
            $ret[$dt->alert_type]['avg_volume_limit2'] = $dt->avg_volume_limit2;
            $ret[$dt->alert_type]['index_limit'] = $dt->index_limit;
            $ret[$dt->alert_type]['server'] = $dt->server_name;
            $ret[$dt->alert_type]['login'] = $dt->login;
        }

        return response()->json($ret);
    }

    public function deleteGlobalAlertOm($request)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $deleteGloablSettingData = $this->globalSettingOm->deleteSetting($server_name, $login_id, $request);

        if (!$deleteGloablSettingData) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.global_alert_delete')),
                    'status_code' => 200
        ]);
    }

    //Bo alert setting

    public function setBoAlert($request)
    {
        //get server name from token
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $res = $this->bolAlertSetting->saveBoAlertSetting($request, $server_name, $login_id);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.bo_alert_setting')),
                    'status_code' => 200
        ]);
    }

    public function getBoAlert()
    {

        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $getGloablSettingData = $this->bolAlertSetting->getBoAlertSetting($server_name, $login_id);

        $ret = [];

        foreach ($getGloablSettingData as $key => $dt) {
            $ret[$dt->alert_type]['volume_limit1'] = $dt->volume_limit1;
            $ret[$dt->alert_type]['volume_limit2'] = $dt->volume_limit2;
            $ret[$dt->alert_type]['avg_volume_limit1'] = $dt->avg_volume_limit1;
            $ret[$dt->alert_type]['avg_volume_limit2'] = $dt->avg_volume_limit2;
            $ret[$dt->alert_type]['index_limit'] = $dt->index_limit;
            $ret[$dt->alert_type]['server'] = $dt->server_name;
            $ret[$dt->alert_type]['symbol'] = $dt->symbol;
            $ret[$dt->alert_type]['login'] = $dt->login;
        }

        return response()->json($ret);
    }

    public function deleteBoAlert($request)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $server_name = $payload->get('server_name');
        $userinfo = JWTAuth::parseToken()->authenticate();
        $login_id = $userinfo->manager_id;
        $deleteGloablSettingData = $this->bolAlertSetting->deleteBoAlertSetting($server_name, $login_id, $request);

        if (!$deleteGloablSettingData) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.bo_alert_setting_delete')),
                    'status_code' => 200
        ]);
    }
    
    //Update password
    
    public function passwordUpdate($request){
        
        //validate
        //Validation
        $validate = Validator::make($request->all(), [
                    "new_password" => 'required',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
        
        $servermgrId = common::serverManagerId();
        $res = $this->usermodel->passwordUpdate($request, $servermgrId['server_name'], $servermgrId['login']);
        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.password_update')),
                    'status_code' => 200
        ]);
    }

}
