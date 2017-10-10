<?php

namespace Fox\Reportgroup\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\ReportGroupContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Requests\reportgroup;
use App\Http\Requests\reportgroup_update;


class ReportGroupController extends Controller
{

    public function __construct(ReportGroupContract $reportGroupContainer)
    {
        $this->reportGroupContainer = $reportGroupContainer;
    }

    /**
     * save group by group name and login
     * 
     * 
     * @param reportgroup $request
     * @return type json
     */
    public function saveGroup(reportgroup $request)
    {        
        return $this->reportGroupContainer->saveReportGroup($request);
    }

    /**
     * update group and login
     * 
     * @param reportgroup_update $request
     * @return type json
     */
    public function updateGroup(reportgroup_update $request)
    {       
       return $this->reportGroupContainer->updateReportGroup($request);
    }
    
    /**
     * Get group,login list by server and managerId
     * 
     * @param type $id
     * @return type json
     */
    public function getTradeGroupList($tradegroupId = null){
     
       return $this->reportGroupContainer->getTradeList($tradegroupId); 
    }
    
    /**
     * Delete record by passing groupId
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function deleteTradeGroupList(Request $request){
      
      return $this->reportGroupContainer->deleteTradeList($request);  
    }
    

}
