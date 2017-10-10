<?php

namespace Fox\Users\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use Fox\Services\Contracts\TabselectContract;

class TabselectController extends Controller
{
    
    public function __construct(TabselectContract $tabselectContainer)
    {
        $this->tabselectContainer = $tabselectContainer;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savetab(Request $request)
    {  
       return $this->tabselectContainer->saveTab($request); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
       return $this->tabselectContainer->getTabSetting(); 
    }
}
