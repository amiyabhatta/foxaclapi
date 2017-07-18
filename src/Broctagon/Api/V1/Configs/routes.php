<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/dashboard', 'Users\Http\Controllers\UserController@dashboard');

Route::get('/login', function () {
    //return view('welcome');
    return View::make('pages.login');
});

Route::get('create/user', function () {
    //return view('welcome');
    return View::make('pages.create_user');
});


Route::group(['prefix' => 'api/v1'], function () {

    Route::post('login', 'Users\Http\Controllers\UserController@login');

    Route::group(['middleware' => 'dycryptjwt', 'jwt-auth'], function () {
        Route::resource('users', 'Users\Http\Controllers\UserController' , ['only' => ['update', 'destroy']]);
        Route::post('register', 'Users\Http\Controllers\UserController@store'); //Save User
        Route::post('assignrole/{user_id}', 'Users\Http\Controllers\UserController@assignRoletoUser');
        
        //Roles
        Route::get('roles', 'Roles\Http\Controllers\RolesController@index');
        Route::post('roles', 'Roles\Http\Controllers\RolesController@store');
        Route::put('roles/{id}', 'Roles\Http\Controllers\RolesController@update');
        Route::delete('roles/{id}', 'Roles\Http\Controllers\RolesController@destroy');
        
        //Permission
        Route::get('permission', 'Permissions\Http\Controllers\PermissionController@index');
        Route::post('permission', 'Permissions\Http\Controllers\PermissionController@store');
        Route::put('permission/{id}', 'Permissions\Http\Controllers\PermissionController@update');
        Route::delete('permission/{id}', 'Permissions\Http\Controllers\PermissionController@destroy');
        
        //Assign Permission to role
        Route::post('assignpermission/{role_id}', 'Roles\Http\Controllers\RolesController@assignPermissiontoRole');
        
        //Gateway Details
        Route::get('gateway', 'Gateway\Http\Controllers\GatewayController@index');
        Route::post('gateway', 'Gateway\Http\Controllers\GatewayController@store');
        Route::put('gateway/{id}', 'Gateway\Http\Controllers\GatewayController@update');
        Route::delete('gateway/{id}', 'Gateway\Http\Controllers\GatewayController@destroy');
        
        //Server Details
        Route::get('server', 'Server\Http\Controllers\ServerController@index');
        Route::post('server', 'Server\Http\Controllers\ServerController@store');
        Route::put('server/{id}', 'Server\Http\Controllers\ServerController@update');
        Route::delete('server/{id}', 'Server\Http\Controllers\ServerController@destroy');
        
    });
});
