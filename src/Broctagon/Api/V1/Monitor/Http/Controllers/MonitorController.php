<?php

namespace Fox\Monitor\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\MonitorContract;
use Illuminate\Http\Request;


class MonitorController extends Controller
{
    
    public function __construct(MonitorContract $monitorContainer)
    {
        $this->monitorContainer = $monitorContainer;
    }
    
    //Set global alert for Overall Monitoring
    public function setGlobalAlertOm(Request $request){
       return $this->monitorContainer->setGlobalAlertOm($request); 
    }
    
    //Get global alert for Overall monitoring
    public function getGlobalAlertOm(){       
       return $this->monitorContainer->getGlobalAlertOm(); 
    }
    //Delete global alert for overall monitoring
    public function deleteglobalalertom(Request $request){
       return $this->monitorContainer->deleteGlobalAlertOm($request);  
    }
    
    //Save bo alert setting
    public function setBoAlert(Request $request){        
       return $this->monitorContainer->setBoAlert($request); 
    }
    
    //Get bo alert setting
     public function getBoAlert(Request $request){       
       return $this->monitorContainer->getBoAlert($request); 
    }
    
    //Delete bo alert setting
    public function deleteBoalert(Request $request){        
       return $this->monitorContainer->deleteBoAlert($request);  
    }

}
