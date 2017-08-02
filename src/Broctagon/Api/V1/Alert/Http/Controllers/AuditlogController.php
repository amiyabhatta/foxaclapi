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
    
    public function save(craeteAuditlog $request){
       
       return $this->alertContainer->saveAuditLog($request);
    }
    
    public function get(Request $request){      
       return $this->alertContainer->getAuditLog($request);
    }
    
}
 