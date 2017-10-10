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
    
    public function __construct($userTransformer, $user)
    {
        $this->userTransformer = $userTransformer;
        $this->usermodel = $user;
    }

    /**
     * Get users details
     * 
     * @param type $id
     * @return Collection
     */
    public function getUsers($userId)
    {
        $limit = Input::get('limit', 20);

        $user = $this->usermodel->getAllUsers($limit, $userId);
        
        $queryParams = array_diff_key(Input::all(), array_flip(['page']));

        $user->appends($queryParams);

        $userAdapter = new IlluminatePaginatorAdapter($user);
        $resource = new Collection($user, $this->userTransformer);
        $resource->setPaginator($userAdapter);

        return $resource;
    }

    /**
     * API Login
     *
     * @param type $request
     * @return type
     */
    public function login($request)
    {

        //$credentials = $request->only('email', 'password');
        $credentials = $request->only('manager_id', 'password');

        $customValue = $request->only('server_name');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials, $customValue)) {

                // return $this->setStatusCode(401)->respondWithError('Invalid Credentials');
                return $this->setStatusCode(200)->respond(['message' => trans('user.invalid_creds'),
                            'status_code' => 404]);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token

            return $this->setStatusCode(200)->respond(['message' => trans('user.not_create_token'),
                        'status_code' => 500]);
        }


        $user = JWTAuth::authenticate($token);
        //if user status is zero then should not login
        if (!$user->activate_status) {
            return $this->setStatusCode(404)->respond(['message' => trans('user.user_not_exist'),
                        'status_code' => 404]);
        }

        $serverName = $request->input('server_name');
        //check server is assign to user
        $checkServer = $this->serverAssigntoUser($user->id, $serverName);
        if (!$checkServer) {
            return $this->setStatusCode(200)->respond(['message' => trans('user.user_not_registered'),
                        'status_code' => 404]);
        }

        $serverDetails = $this->usermodel->getUserServerDetails($user->id, $serverName);
        $tabDetails = $this->usermodel->getUserPermissionDetails($user->id);
        $gatewayDetails = $this->usermodel->getUserGatewayDetails($serverName);
        $dbDetials = $this->usermodel->getDbDetails();
        $mailSetting = $this->usermodel->getMailSetting($user->id, $serverName);
        $token = encrypt($token);
        // all good so return the token
        return $this->setStatusCode(200)->respondWithToken(compact('token', 'serverDetails', 'tabDetails', 'gatewayDetails', 'dbDetials', 'mailSetting'));
    }

    /**
     * create user
     * 
     * 
     * @param type $request
     * @return type json
     */
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

    /**
     * Update user details
     * 
     * @param type $request
     * @return type json
     */
    public function updateUser($request)
    {


        if ($request->input('password')) {
            $validate = Validator::make($request->all(), [
                        "confirm_password" => 'required|same:password',
            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors(), 422);
            }
        }

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

    /**
     * Delete user e.g soft delete
     * 
     * @param type $request
     * @return type json
     */
    public function deleteUser($request)
    {

        $res = $this->usermodel->deleteUser($request);

        if (!$res) {

            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.not_found'),
                        'status_code' => 404
            ]);
        } elseif ($res === 'error') {
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

    /**
     * Assign role to user
     * 
     * @param type $request
     * @return type json
     */
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

    /**
     * logout user
     * 
     * @return type json
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->respond(['status_code' => 401, 'message' => trans('user.logout')]);
    }

    /**
     * Login in userInterface
     * 
     * @param type $request
     * @return type json
     */
    public function uiLogin($request)
    {
        
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {

                // return $this->setStatusCode(401)->respondWithError('Invalid Credentials');
                return $this->setStatusCode(404)->respond(['message' => trans('user.invalid_creds')]);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token

            return $this->setStatusCode(200)->respond(['message' => trans('user.not_create_token'),
                        'status_code' => 500]);
        }

        $token = encrypt($token);
        // all good so return the token
        return $this->setStatusCode(200)->respondWithToken(compact('token'));
    }
    
    /**
     * Update|change password for any user
     * 
     * @param type $request
     * @return type json
     */
    public function passwordUpdate($request)
    {

        //validate
        //Validation
        $validate = Validator::make($request->all(), [
                    "new_password" => 'required',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        $res = $this->usermodel->passwordUpdate($request, $servermgrId['login']);
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

    /**
     * Assign server to user
     * @param type $userId
     * @param type $serverName
     * @return type json
     */
    public function serverAssigntoUser($userId, $serverName)
    {
        return $this->usermodel->checkServerAssign($userId, $serverName);
    }

}
