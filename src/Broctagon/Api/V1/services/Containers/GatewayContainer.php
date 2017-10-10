<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\GatewayContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Fox\Common\Common;

class GatewayContainer extends Base implements GatewayContract
{

    protected $gatewayTransformer;
    private $gatewaymodel;

    public function __construct($gatewayTransformer, $gateway)
    {
        $this->gatewayTransformer = $gatewayTransformer;
        $this->gatewaymodel = $gateway;
    }

    /**
     * gateway list
     * 
     * @param type $id
     * @return Collection|json
     */
    public function gatewaytList($gatewayId)
    {
        $limit = Input::get('limit', 20);

        $gwList = $this->gatewaymodel->getAllGwList($limit, $gatewayId);

        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $gwList->appends($queryParams);

        $gwAdapter = new IlluminatePaginatorAdapter($gwList);
        $resource = new Collection($gwList, $this->gatewayTransformer);
        $resource->setPaginator($gwAdapter);

        return $resource;
    }

    /**
     * Create Gateway
     *
     * @param type $request
     * @return type json
     */
    public function createGateway($request)
    {


        $res = $this->gatewaymodel->addGateway($request);


        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.gateway_create_sucess'),
                    'status_code' => 201
        ]);
    }

    /**
     * update gateway
     * 
     * @param type $request
     * @return type json
     */
    public function updateGateway($request)
    {



        $res = $this->gatewaymodel->updateGateway($request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.gateway_update_sucess'),
                    'status_code' => 201
        ]);
    }

    /**
     * Delete Gateway
     * 
     * @param type $id
     * @return type json
     */
    public function deleteGateway($gatewayId)
    {
        
        $res = $this->gatewaymodel->deleteGateway($gatewayId);

        if (!$res) {

            return $this->setStatusCode(404)->respond([
                        'message' => trans('user.not_found'),
                        'status_code' => 404
            ]);
        }

        return $this->setStatusCode(201)->respond([
                    'message' => trans('user.gateway_delete_sucess'),
                    'status_code' => 201
        ]);
    }

}
