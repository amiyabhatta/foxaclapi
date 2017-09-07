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

        $server = $this->servermodel->getAllServerList($limit, $id);

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
    public function getServerListForUserCreate()
    {
        return response()->json($this->servermodel->getAllServerLists());
    }

    public function createServer($request)
    {

        $messsages = array(
            'servername.required' => 'The server name field is required.',
            'servername.unique' => 'Server name already taken',
            'ipaddress.required' => 'The IP address field is required.',
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.',
            'databasename.required' => 'The database name field is required.',
            'masterid.numeric' => 'The masterid must be a number.',
            'GatewayID.required' => 'The gateway id field is required.',
            'GatewayID.exists' => 'The selected gateway id is invalid.'
        );

        $rules = array(
            'servername' => 'required|unique:serverlist,servername',
            'ipaddress' => 'required',
            'username' => 'required',
            'password' => 'required',
            'databasename' => 'required',
            'masterid' => 'numeric',
            'GatewayID' => 'required|exists:mt4gateway,id'
        );

        $validate = Validator::make($request->all(), $rules, $messsages);

        if ($validate->fails()) {
            //return $validate->errors();
            return response()->json($validate->errors(), 422);
        }


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

    public function updateServer($request)
    {
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

    public function deleteServer($id)
    {

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

}
