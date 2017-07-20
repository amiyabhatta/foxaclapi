<?php

namespace Fox\Server\Http\Controllers;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Fox\Services\Contracts\ServerContract;
use App\Http\Requests\serverlist;
use App\Http\Requests\serverlistUpdate;


class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ServerContract $serverContainer, Manager $manager)
    {
        $this->serverContainer = $serverContainer;
        $this->fractal = $manager;        
    } 
    
    public function index($id = NULL)
    {
        return $this->fractal->createData($this->serverContainer->getServerList($id))->toJson();
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
    public function store(serverlist $request)
    {
       return $this->serverContainer->createServer($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(serverlistUpdate $request)
    {
        return $this->serverContainer->updateServer($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      return $this->serverContainer->deleteServer($id); 
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function serverlist(){
       return $this->serverContainer->getServerListForUserCreate();  
    }
}
