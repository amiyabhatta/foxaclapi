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
    
    public function saveTradealertDiscrad(Request $request){
       return $this->alertContainer->saveTradeAlertDiscrad($request);
    }
    
    public function getTradealertDiscrad(Request $request){
       return $this->alertContainer->getTradeAlertDiscrad($request);
    }
}
