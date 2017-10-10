<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\ReportGroupContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;
use Fox\Common\Foxvalidation;

class ReportgroupContainer extends Base implements ReportGroupContract
{
    use Foxvalidation;
    
    public function __construct($reportgroup, $reportgroupuser)
    {
        $this->reportgroup = $reportgroup;
        $this->reportgroupuser = $reportgroupuser;
    }
    
    /**
     * Save report group
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function saveReportGroup($request)
    {
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
    
    /**
     * update report group by groupname
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function updateReportGroup($request)
    {

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
    
    /**
     * Get trade list by id
     * 
     * 
     * @param type $id
     * @return type json
     */
    public function getTradeList($reportGroupId)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->reportgroup->getTradeGrpList($servermgrId['server_name'], $servermgrId['login'], $reportGroupId);

        return $res;
    }
    
    /**
     * Delete tradelist by groupid
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function deleteTradeList($request)
    {

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
}
