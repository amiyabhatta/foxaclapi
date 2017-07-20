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
use Fox\Services\Contracts\ServerContract;

class ServerContainer extends Base implements ServerContract
{

    protected $serverTransformer;
    private $servermodel;    

    public function __construct($serverTransformer, $server)
    {
        $this->serverTransformer = $serverTransformer;
        $this->servermodel = $server;
    }

    /**
     * Get Users.
     * Paginator adapter is used for pagination.     * 
     * @return Collection
     */
    public function getServerList($id)
    {
        $limit = Input::get('limit', 20);
        
        $server = $this->servermodel->getAllServerList($limit,$id);

        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $server->appends($queryParams);

        $serverAdapter = new IlluminatePaginatorAdapter($server);
        $resource = new Collection($server, $this->serverTransformer);
        $resource->setPaginator($serverAdapter);

        return $resource;
    }

    /**
     * Login
     *
     * @param type $request
     * @return type
     */
    
    public function getServerListForUserCreate(){       
        return response()->json($this->servermodel->getAllServerLists());
    }
    
    
    public function createServer($request){
        
        //check user permission (only Superadmin having permission)
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

           $res = $this->servermodel->addServer($request);

        
            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(201)->respond([
                        'message' => trans('user.server_create_sucess'),
                        'status_code' => 201
            ]);
        }

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    public function updateServer($request){
        
         //check user permission (only Superadmin having permission)
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->servermodel->updateServer($request);

            if (!$res) {
                return $this->setStatusCode(500)->respond([
                            'message' => trans('user.some_error_occur'),
                            'status_code' => 500
                ]);
            }

            return $this->setStatusCode(201)->respond([
                        'message' => trans('user.server_update_sucess'),
                        'status_code' => 201
            ]);
        }

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    public function deleteServer($id){
        
        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {

            $res = $this->servermodel->deleteServer($id);

            if (!$res) {
                
                return $this->setStatusCode(404)->respond([
                            'message' => trans('user.server_not_found'),
                            'status_code' => 404
                ]);
            }            
            
            return $this->setStatusCode(201)->respond([
                        'message' => trans('user.server_delete_sucess'),
                        'status_code' => 201
            ]);
        }

        return $this->setStatusCode(403)->respond([
                    'message' => trans('user.permission_denied'),
                    'status_code' => 403
        ]);
    }
    
    

}
