<?php

namespace Fox\Permissions\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use Fox\Services\Contracts\PermissionContract;
use App\Http\Requests\Permission;
use App\Http\Requests\updatePermission;

class PermissionController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(PermissionContract $permissionContainer)
    {
        $this->permissionContainer = $permissionContainer;         
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($permissionId = null)
    {
       return $this->permissionContainer->getPermission($permissionId);
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
    public function store(Permission $request)
    {        
        return $this->permissionContainer->createPermission($request); 
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updatePermission $request)
    {
        return $this->permissionContainer->updatePermission($request); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       return $this->permissionContainer->deletePermission($request); 
    }
}
