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

    public function getLastTrade($id = NULL)
    {
        return $this->alertContainer->getLastTradeList($id);
    }

    public function updateLastTrade($id, Request $request)
    {
        return $this->alertContainer->updateLastTradeList($id, $request);
    }

    public function createWhitelabel(createWhiteLabel $request)
    {
        return $this->alertContainer->createWhiteLabel($request);
    }

    public function updateWhitelabel(updateWhiteLabel $request, $id)
    {
        return $this->alertContainer->updateWhiteLabel($request, $id);
    }
    
    public function deleteWhitelabel($id){
        return $this->alertContainer->deleteWhiteLabel($id);
    }

}
