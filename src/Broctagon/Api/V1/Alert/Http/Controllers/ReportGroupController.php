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

    public function saveGroup(reportgroup $request)
    {
        return $this->alertContainer->saveReportGroup($request);
    }

    public function updateGroup(reportgroup_update $request)
    {       
       return $this->alertContainer->updateReportGroup($request);
    }
    
    public function getTradeGroupList($id = null){
       return $this->alertContainer->getTradeList($id); 
    }
    
    public function deleteTradeGroupList(Request $request){
      return $this->alertContainer->deleteTradeList($request);  
    }
    

}
