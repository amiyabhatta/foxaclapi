<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\RoleContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Fox\Common\Common;

class RoleContainer extends Base implements RoleContract
{

    //protected $userTransformer;
    //private $usermodel;
    private $roleModel;

    public function __construct($role, $roleTransformer)
    {
        $this->roleModel = $role;
        $this->roleTransformer = $roleTransformer;
    }

    /*
     * Get Users.
     * Paginator adapter is used for pagination.    
     * @return Collection
     */

    public function createRole($request)
    {
        $res = $this->roleModel->addRole($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.role_created'),
                    'status_code' => 201
        ]);
    }

    /*
     * Get Users.
     * Paginator adapter is used for pagination.    
     * @return Collection
     */

    public function updateRole($request)
    {        
        $res = $this->roleModel->updateRole($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.role_update_sucess'),
                    'status_code' => 201
        ]);
    }

    public function deleteRole($request)
    {
        $res = $this->roleModel->deleteRole($request);

        if (!$res) {

            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.role_not_found'),
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
                    'message' => trans('user.role_delete_sucess'),
                    'status_code' => 201
        ]);
    }

    /**
     * Get Roles
     * Paginator adapter is used for pagination.    
     * @return Collection
     */
    public function getRoles($id)
    {

        $limit = Input::get('limit', 20);

        $role = $this->roleModel->getAllRoles($limit, $id);

        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $role->appends($queryParams);

        $roleAdapter = new IlluminatePaginatorAdapter($role);
        $resource = new Collection($role, $this->roleTransformer);
        $resource->setPaginator($roleAdapter);

        return $resource;
    }

    /**
     * 
     * Paginator adapter is used for pagination.    
     * @return Collection
     */
    public function assignPermRole($request)
    {
        $res = $this->roleModel->assignRoletoPerm($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (($request->input('action')) ? trans('user.perm_assign_role') : trans('user.perm_remove_role')),
                    'status_code' => 200
        ]);
    }

}
