<?php

namespace Fox\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\UserContract;
use League\Fractal\Manager;
use App\Http\Requests\LoginUser;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;
use App\Http\Requests\assignRoleToUser;
use Illuminate\Support\Facades\View;
use App\Http\Requests\UiLogin;

class UserController extends Controller
{

    public function __construct(UserContract $userContainer, Manager $manager)
    {
        $this->userContainer = $userContainer;
        $this->fractal = $manager;
    }

    /**
     * List all users
     * @author Dibya lochan Nayak <dibyalochan.nayak@broctagon.com>
     * @return Json
     */
    public function index($id = NUll)
    {       
        return $this->fractal->createData($this->userContainer->getUsers($id))->toJson();
    }

    /**
     * @param LoginUser $request
     * @author Dibya lochan Nayak <dibyalochan.nayak@broctagon.com>
     * @return type
     */
    public function login(LoginUser $request)
    {
        return $this->userContainer->login($request);
    }

    /**
     * @param Request $request     *
     * @return type json
     */
    public function store(StoreUser $request)
    {
        return $this->userContainer->createUser($request);
    }

    /**
     * @param Request $request     *
     * @return type json
     */
    public function update(UpdateUser $request)
    {        
        return $this->userContainer->updateUser($request);
    }
    
    public function destroy(Request $request){        
      
        return $this->userContainer->deleteUser($request);
    }
    
    public function assignRoletoUser(assignRoleToUser $request){
       return $this->userContainer->assignRole($request); 
    }
   
    //Show all User
    public function dashboard(){       
       $user_data = $this->fractal->createData($this->userContainer->getUsers())->toarray();
       
       return View::make('pages.home')->with(compact('user_data'));
    }
    
    public function logout(){
        
        return $this->userContainer->logout();
        
    }

    public function uilogin(UiLogin $request){         
       return $this->userContainer->Uilogin($request); 
    }
    
    //Set global alert for Overall Monitoring
    public function setGlobalAlertOm(Request $request){         
       return $this->userContainer->setGlobalAlertOm($request); 
    }
    
    //Get global alert for Overall monitoring
    public function getGlobalAlertOm(){       
       return $this->userContainer->getGlobalAlertOm(); 
    }
    //Delete global alert for overall monitoring
    public function deleteglobalalertom(Request $request){
       return $this->userContainer->deleteGlobalAlertOm($request);  
    }
    
    //Save bo alert setting
    public function setBoAlert(Request $request){        
       return $this->userContainer->setBoAlert($request); 
    }
    
    //Get bo alert setting
     public function getBoAlert(Request $request){       
       return $this->userContainer->getBoAlert($request); 
    }
    
    //Delete bo alert setting
    public function deleteBoalert(Request $request){        
       return $this->userContainer->deleteBoAlert($request);  
    }
       
}
