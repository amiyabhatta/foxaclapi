<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AuditlogContract;
use Illuminate\Http\Request;
use App\Http\Requests\craeteAuditlog;

class AuditlogController extends Controller
{

    public function __construct(AuditlogContract $auditlogContainer)
    {
        $this->auditlogContainer = $auditlogContainer;
    }

    /**
     * save data in auditlog
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param craeteAuditlog $request
     * @return type json
     */
    public function save(craeteAuditlog $request)
    {

        return $this->auditlogContainer->saveAuditLog($request);
    }

    /**
     * get data
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param Request $request
     * @return type json
     */
    public function get(Request $request)
    {
        return $this->auditlogContainer->getAuditLog($request);
    }

}
