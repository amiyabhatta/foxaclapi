<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Fox\Common\Common;
use Fox\Services\Contracts\PermissionContract;


class PermissionContainer extends Base implements PermissionContract
{

    //protected $permissionTransformer;
    //private $usermodel;
    //private $roleModel;
    
    public function __construct($permission, $permissionTransformer, $manager)
    {       
        $this->permissionModel = $permission;
        $this->permissionTransformer = $permissionTransformer;
        $this->fractal = $manager;
    }

    /*
     * Get Users.
     * Paginator adapter is used for pagination.    
     * @return Collection
     */
    
    public function createPermission($request){
        
       //check user permission (only Superadmin having permission)
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->permissionModel->addPermission($request);

            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(201)->respond([
                        'message' => trans('user.permission_create_sucess'),
                        'status_code' => 201
            ]);
        }

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    /*
     * Get Users.
     * Paginator adapter is used for pagination.    
     * @return Collection
     */
    
    public function updatePermission($request){
        
       //check user permission (only Superadmin having permission)
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->permissionModel->updatePermission($request);

            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(201)->respond([
                        'message' => trans('user.permission_update_sucess'),
                        'status_code' => 201
            ]);
        }

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    
    public function deletePermission($request){
        
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->permissionModel->deletePermission($request);

            if (!$res) {
                
                return $this->setStatusCode(404)->respond([
                            'message' => trans('user.permission_not_found'),
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
                        'message' => trans('user.permission_delete_sucess'),
                        'status_code' => 201
            ]);
        }

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    /**
     * Get All permission
     * Paginator adapter is used for pagination.    
     * @return Collection
     */
    public function getPermission($id = NULL)
    {
        
        $limit = Input::get('limit', 20);
       
        $permission = $this->permissionModel->getAllPermission($limit,$id);
        
        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $permission->appends($queryParams);

        $permissionAdapter = new IlluminatePaginatorAdapter($permission);
        $resource = new Collection($permission, $this->permissionTransformer);
        $resource->setPaginator($permissionAdapter);

        $resource = $this->fractal->createData($resource)->toJson();
        return $resource;
    }
    
    
    
}
