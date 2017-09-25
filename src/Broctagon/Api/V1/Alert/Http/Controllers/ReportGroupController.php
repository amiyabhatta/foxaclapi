<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Requests\reportgroup;
use App\Http\Requests\reportgroup_update;


class ReportGroupController extends Controller
{

    public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
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
        return $this->alertContainer->saveReportGroup($request);
    }

    /**
     * update group and login
     * 
     * @param reportgroup_update $request
     * @return type json
     */
    public function updateGroup(reportgroup_update $request)
    {       
       return $this->alertContainer->updateReportGroup($request);
    }
    
    /**
     * Get group,login list by server and managerId
     * 
     * @param type $id
     * @return type json
     */
    public function getTradeGroupList($id = null){
       return $this->alertContainer->getTradeList($id); 
    }
    
    /**
     * Delete record by passing groupId
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function deleteTradeGroupList(Request $request){
      
      return $this->alertContainer->deleteTradeList($request);  
    }
    

}
