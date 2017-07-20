<?php

namespace Fox\Gateway\Http\Controllers;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Fox\Services\Contracts\GatewayContract;
use App\Http\Requests\Mt4gateway;
use App\Http\Requests\Mt4gatewayUpdate;


class GatewayController extends Controller
{
    
    
   public function __construct(GatewayContract $gatewayContainer, Manager $manager)
    {
        $this->gatewayContainer = $gatewayContainer;
        $this->fractal = $manager;
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = NULL)
    {
       
       return $this->fractal->createData($this->gatewayContainer->gatewaytList($id))->toJson();
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
    public function store(Mt4gateway $request)
    {
        return $this->gatewayContainer->createGateway($request);
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
    public function update(Mt4gatewayUpdate $request)
    {
        return $this->gatewayContainer->updateGateway($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {       
       return $this->gatewayContainer->deleteGateway($id);
    }
}
