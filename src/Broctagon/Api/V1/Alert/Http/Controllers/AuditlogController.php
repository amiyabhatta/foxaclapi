<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Requests\craeteAuditlog;

class AuditlogController extends Controller
{

    public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
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

        return $this->alertContainer->saveAuditLog($request);
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
        return $this->alertContainer->getAuditLog($request);
    }

}
