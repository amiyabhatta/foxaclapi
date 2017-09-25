<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;

class TradealertdiscardController extends Controller
{
    public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
    }
    
    /**
     * Save trade alert discrad by passing only ticket
     * 
     * @param Request $request
     * @return type json
     */
    
    public function saveTradealertDiscrad(Request $request){
       return $this->alertContainer->saveTradeAlertDiscrad($request);
    }
    
    /**
     * get trade alert discrad by passing addedon e.g addedon = "2017-09-15 13:51:04"
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function getTradealertDiscrad(Request $request){
       return $this->alertContainer->getTradeAlertDiscrad($request);
    }
}
