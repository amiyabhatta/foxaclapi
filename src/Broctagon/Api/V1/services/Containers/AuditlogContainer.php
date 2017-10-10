<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\AuditlogContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;

class AuditlogContainer extends Base implements AuditlogContract
{

    
    public function __construct($auditlog)
    {
        $this->auditlog = $auditlog;
    }
    
    /**
     * Save data fro Audit log
     * 
     * @param type $request
     * @return type json
     */
    public function saveAuditLog($request)
    {
        $servermgrId = common::serverManagerId();

        $res = $this->auditlog->saveAuditLog($servermgrId['server_name'], $request);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.audit_log_created')),
                    'status_code' => 200
        ]);
    }

    /**
     * Get data from Auditlog by different parameter
     * 
     * 
     * @param type $request
     * @return type json
     */
    public function getAuditLog($request)
    {

        $servermgrId = common::serverManagerId();



        if (empty($request->input('start_date') && $request->input('end_date'))) {
            if (empty($request->input('start_date'))) {
                return $this->setStatusCode(200)->respond([
                            'message' => (trans('user.audit_start_date')),
                ]);
            }
            if (empty($request->input('end_date'))) {
                return $this->setStatusCode(200)->respond([
                            'message' => (trans('user.audit_end_date')),
                ]);
            }
        }
        //Compaire date filed

        $res = $this->auditlog->getAuditLog($servermgrId['server_name'], $request);
        return $res;
    } 

    
}
