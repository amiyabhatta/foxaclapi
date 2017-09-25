<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Requests\user_trade;
use App\Http\Requests\user_trade_update;

class AlertController extends Controller
{

    public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
    }

    public function index()
    {
        
    }

    /**
     * save user trade
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param user_trade $request
     * @return type json
     */
    public function saveUserTrade(user_trade $request)
    {
        return $this->alertContainer->saveuserTrades($request);
    }

    /**
     * update user trade
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param user_trade_update $request
     * @param type $login
     * @return type json
     */
    public function updateUserTrade(user_trade_update $request, $login)
    {
        return $this->alertContainer->updateuserTrades($request, $login);
    }

    /**
     * delete user trade
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param type $login
     * @return type json
     */
    public function deleteUserTrade($login = NULL)
    {
        return $this->alertContainer->deleteuserTrades($login);
    }

    /**
     * Get Trade Alert
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param type $id
     * @return type json
     */
    public function getTradeAlert($id = NULL)
    {
        return $this->alertContainer->getuserTrades($id);
    }

    /**
     * get login by comman separeted
     * 
     * @author Amiya Bhatta <amiya.bhatta@broctagon.com>
     * @param type NULL
     * @return type json
     */
    public function getLogin()
    {

        return $this->alertContainer->getLogin();
    }

}
