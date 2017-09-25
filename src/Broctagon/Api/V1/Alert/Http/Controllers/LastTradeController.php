<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Requests\user_trade;
use App\Http\Requests\createWhiteLabel;
use App\Http\Requests\updateWhiteLabel;

class LastTradeController extends Controller
{

    public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
    }

    /**
     * Get Last trade 
     * 
     * 
     * @param type $id
     * @return type json
     */
    public function getLastTrade($id = NULL)
    {
        return $this->alertContainer->getLastTradeList($id);
    }

    /**
     * update last trade 
     * 
     * 
     * @param type $id
     * @param Request $request
     * @return type json
     */
    public function updateLastTrade($id, Request $request)
    {
        return $this->alertContainer->updateLastTradeList($id, $request);
    }

    /**
     * create whitelabel
     * 
     * @param createWhiteLabel $request
     * @return type json
     */
    public function createWhitelabel(createWhiteLabel $request)
    {
        return $this->alertContainer->createWhiteLabel($request);
    }

    /**
     * update whitelabel
     * 
     * @param updateWhiteLabel $request
     * @param type $id
     * @return type json
     */
    public function updateWhitelabel(updateWhiteLabel $request, $id)
    {
        return $this->alertContainer->updateWhiteLabel($request, $id);
    }

    /**
     * delete whitelabel by id
     * 
     * @param type $id
     * @return type json
     */
    public function deleteWhitelabel($id)
    {
        return $this->alertContainer->deleteWhiteLabel($id);
    }

    /**
     * Get whitelabel by Id
     * 
     * @param type $id
     * @return type json
     */
    public function getWhitelabel($id = null)
    {
        return $this->alertContainer->getWhiteLabel($id);
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
        return $this->alertContainer->getLastTradeWlEmailAlert($request);
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
        return $this->alertContainer->saveLastTradeWlEmailAlert($request);
    }

}
