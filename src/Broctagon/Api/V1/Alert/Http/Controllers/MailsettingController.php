<?php

namespace Fox\Alert\Http\Controllers;

use App\Http\Controllers\Controller;
use Fox\Services\Contracts\AlertContract;
use League\Fractal\Manager;
use Illuminate\Http\Request;

class mailsettingController extends Controller
{
    public function __construct(AlertContract $alertContainer, Manager $manager)
    {
        $this->alertContainer = $alertContainer;
        $this->fractal = $manager;
    }
    
    /**
     * save data for mail for different server
     * 
     * 
     * @param Request $request
     * @return type json
     */
    public function saveMailSetting(Request $request){
       return $this->alertContainer->saveMailSetting($request);
    }
}
