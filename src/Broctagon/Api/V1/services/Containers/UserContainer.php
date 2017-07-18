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

    public function __construct($userTransformer, $user)
    {
        $this->userTransformer = $userTransformer;
        $this->usermodel = $user;
    }

    /**
     * Get Users.
     * Paginator adapter is used for pagination.
     * @author Dibya lochan Nayak <dibyalochan.nayak@broctagon.com>
     * @return Collection
     */
    public function getUsers()
    {
        $limit = Input::get('limit', 20);

        $user = $this->usermodel->getAllUsers($limit);

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
        
        $user = JWTAuth::authenticate($token);
        $server_name = $request->input('server_name');
        $server_details = $this->usermodel->getUserServerDetails($user->id,$server_name);
        $tab_details = $this->usermodel->getUserPermissionDetails($user->id);
        
        $token = encrypt($token);
        // all good so return the token
        return $this->setStatusCode(200)->respondWithToken(compact('token','server_details','tab_details'));
    }
    
    
    
    
    
    public function createUser($request){
        
        //check user permission (only Superadmin having permission)
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

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

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    public function updateUser($request){
        
         //check user permission (only Superadmin having permission)
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

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

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    public function deleteUser($request){
        
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

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

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    public function assignRole($request){
       $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

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

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);  
    }

}
