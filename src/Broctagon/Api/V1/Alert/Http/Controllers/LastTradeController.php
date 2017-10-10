<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\LastTradeContract;
use Illuminate\Http\Request;
use App\Http\Requests\user_trade;
use App\Http\Requests\createWhiteLabel;
use App\Http\Requests\updateWhiteLabel;
use Fox\Services\Contracts\WlEmailAlertContract;

class LastTradeController extends Controller
{

    public function __construct(LastTradeContract $lastTradeContainer,WlEmailAlertContract $wlTradeEmailAlert)
    {
        $this->lastTradeContainer = $lastTradeContainer;
        $this->WlEmailAlertContract = $wlTradeEmailAlert;
        
    }

    /**
     * Get Last trade 
     * 
     * 
     * @param type $lastTradeId
     * @return type json
     */
    public function getLastTrade($lastTradeId = NULL)
    {
        return $this->lastTradeContainer->getLastTradeList($lastTradeId);
    }

    /**
     * update last trade 
     * 
     * 
     * @param type $lastTradeId
     * @param Request $request
     * @return type json
     */
    public function updateLastTrade($lastTradeId, Request $request)
    {
        return $this->lastTradeContainer->updateLastTradeList($lastTradeId, $request);
    }

    /**
     * create whitelabel
     * 
     * @param createWhiteLabel $request
     * @return type json
     */
    public function createWhitelabel(createWhiteLabel $request)
    {
        return $this->lastTradeContainer->createWhiteLabel($request);
    }

    /**
     * update whitelabel
     * 
     * @param updateWhiteLabel $request
     * @param type $wlId
     * @return type json
     */
    public function updateWhitelabel(updateWhiteLabel $request,$wlId)
    {
        return $this->lastTradeContainer->updateWhiteLabel($request, $wlId);
    }

    /**
     * delete whitelabel by id
     * 
     * @param type $wlId
     * @return type json
     */
    public function deleteWhitelabel($wlId)
    {
        return $this->lastTradeContainer->deleteWhiteLabel($wlId);
    }

    /**
     * Get whitelabel by Id
     * 
     * @param type $id
     * @return type json
     */
    public function getWhitelabel($wlId = null)
    {
        return $this->lastTradeContainer->getWhiteLabel($wlId);
    }

    /**
     * Get last trade email alert
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function getLastTradeEmailAlert(Request $request)
    {
        return $this->WlEmailAlertContract->getLastTradeWlEmailAlert($request);
    }

    /**
     * Save last trade email alert
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function saveLastTradeEmailAlert(Request $request)
    {
        return $this->WlEmailAlertContract->saveLastTradeWlEmailAlert($request);
    }

}
