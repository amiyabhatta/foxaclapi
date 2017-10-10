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
     * 
     * @param type $id
     * @return type json
     */
    public function index($userId = NUll)
    {   
       return $this->fractal->createData($this->userContainer->getUsers($userId))->toJson();
    }

    /**
     * @param LoginUser $request
     * 
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
    
    /**
     * Delete user
     * 
     * @param Request $request
     * @return type json
     */
    public function destroy(Request $request){        
      
        return $this->userContainer->deleteUser($request);
    }
    
    /**
     * Assign Role to user
     * 
     * @param assignRoleToUser $request
     * @return type json
     */
    public function assignRoletoUser(assignRoleToUser $request){
       return $this->userContainer->assignRole($request); 
    }
   
    //Show all User
    public function dashboard(){       
       $userData = $this->fractal->createData($this->userContainer->getUsers())->toarray();
       
       return View::make('pages.home')->with(compact('userData'));
    }
    
    /**
     * Delete authentication 
     * 
     * @return type json
     */
    public function logout(){
        
        return $this->userContainer->logout();
    }

    /**
     * login into Ui part
     * 
     * 
     * @param UiLogin $request
     * @return type json
     */
    public function uilogin(UiLogin $request){         
       return $this->userContainer->uiLogin($request); 
    }
    
    /**
     * Update user password
     * 
     * @param Request $request
     * @return type json
     */
    public function passwordUpdate(Request $request){
      return $this->userContainer->passwordUpdate($request);  
    }
       
}
