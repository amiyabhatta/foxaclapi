<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Requests\user_trade;



class AlertController extends Controller
{
     public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
    }
    
    public function index(){
        
    }
    
    public function saveUserTrade(user_trade $request){
       return $this->alertContainer->saveuserTrades($request);
    }
    
    public function updateUserTrade(user_trade $request, $login ){
       return $this->alertContainer->updateuserTrades($request, $login);
    }
    
    public function deleteUserTrade($id = NULL){       
       return $this->alertContainer->deleteuserTrades($id);
    }
    
    public function getTradeAlert($id = NULL){
       return $this->alertContainer->getuserTrades($id);
    }
    
    //get login by comman separeted
    public function getLogin(){
       
       return $this->alertContainer->getLogin(); 
    }
}
