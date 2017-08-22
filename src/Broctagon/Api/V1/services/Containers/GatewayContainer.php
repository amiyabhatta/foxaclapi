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
     * Get Users.
     * Paginator adapter is used for pagination.
     * @author Dibya lochan Nayak <dibyalochan.nayak@broctagon.com>
     * @return Collection
     */
    public function gatewaytList($id)
    {
        $limit = Input::get('limit', 20);

        $gw = $this->gatewaymodel->getAllGwList($limit, $id);

        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $gw->appends($queryParams);

        $gwAdapter = new IlluminatePaginatorAdapter($gw);
        $resource = new Collection($gw, $this->gatewayTransformer);
        $resource->setPaginator($gwAdapter);

        return $resource;
    }

    /**
     * Create Gateway
     *
     * @param type $request
     * @return type
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

    public function deleteGateway($id)
    {



        $res = $this->gatewaymodel->deleteGateway($id);

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
