<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\TradeAlertDiscardContract;
use Illuminate\Http\Request;

class TradealertdiscardController extends Controller
{
    public function __construct(TradeAlertDiscardContract $tradealertDiscard)
    {
        $this->tradealertDiscard = $tradealertDiscard;
    }
    
    /**
     * Save trade alert discrad by passing only ticket
     * 
     * @param Request $request
     * @return type json
     */
    
    public function saveTradealertDiscrad(Request $request){
       return $this->tradealertDiscard->saveTradeAlertDiscrad($request);
    }
    
    /**
     * get trade alert discrad by passing addedon e.g addedon = "2017-09-15 13:51:04"
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function getTradealertDiscrad(Request $request){
       return $this->tradealertDiscard->getTradeAlertDiscrad($request);
    }
}
