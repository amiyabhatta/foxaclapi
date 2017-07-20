<?php

namespace Fox\Roles\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fox\Services\Contracts\RoleContract;
use App\Http\Requests\Role;
use App\Http\Requests\updateRole;
use League\Fractal\Manager;
use App\Http\Requests\assignPermissionRole;

use App\Http\Requests;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RoleContract $roleContainer,Manager $manager)
    {
        $this->roleContainer = $roleContainer; 
        $this->fractal = $manager;
    }
    
    
    public function index($id = null)
    {           
        return $this->fractal->createData($this->roleContainer->getRoles($id))->toJson();  
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
    public function store(Role $request)
    {
        return $this->roleContainer->createRole($request); 
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateRole $request)
    {
        return $this->roleContainer->updateRole($request); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return $this->roleContainer->deleteRole($request);
    }
    
    
    /**
     * Assign Permission to role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignPermissiontoRole(assignPermissionRole $request){
        return $this->roleContainer->assignPermRole($request);
    }
}
