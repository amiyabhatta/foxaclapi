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

Route::get('/', function () {
    //return view('welcome');
    return View::make('pages.home');
});
Route::get('/home', function () {
    //return view('welcome');
    return View::make('pages.home');
});
Route::get('/login', function () {
    //return view('welcome');
    return View::make('pages.login');
});

Route::group(['prefix' => 'api/v1'], function () {

    Route::post('login', 'Users\Http\Controllers\UserController@login');
    Route::post('uilogin', 'Users\Http\Controllers\UserController@uilogin');

    Route::group(['middleware' => 'dycryptjwt', 'jwt-auth'], function () {
        Route::resource('users', 'Users\Http\Controllers\UserController', ['only' => ['update', 'destroy']]);
        Route::post('register', 'Users\Http\Controllers\UserController@store'); //Save User
        Route::post('assignrole/{user_id}', 'Users\Http\Controllers\UserController@assignRoletoUser');
        Route::get('users/{id?}', 'Users\Http\Controllers\UserController@index');

        //Roles
        Route::get('roles/{id?}', 'Roles\Http\Controllers\RolesController@index');
        Route::post('roles', 'Roles\Http\Controllers\RolesController@store');
        Route::put('roles/{id}', 'Roles\Http\Controllers\RolesController@update');
        Route::delete('roles/{id}', 'Roles\Http\Controllers\RolesController@destroy');

        //Permission
        Route::get('permission/{id?}', 'Permissions\Http\Controllers\PermissionController@index');
        Route::post('permission', 'Permissions\Http\Controllers\PermissionController@store');
        Route::put('permission/{id}', 'Permissions\Http\Controllers\PermissionController@update');
        Route::delete('permission/{id}', 'Permissions\Http\Controllers\PermissionController@destroy');

        //Assign Permission to role
        Route::post('assignpermission/{role_id}', 'Roles\Http\Controllers\RolesController@assignPermissiontoRole');

        //Gateway Details
        Route::get('gateway/{id?}', 'Gateway\Http\Controllers\GatewayController@index');
        Route::post('gateway', 'Gateway\Http\Controllers\GatewayController@store');
        Route::put('gateway/{id}', 'Gateway\Http\Controllers\GatewayController@update');
        Route::delete('gateway/{id}', 'Gateway\Http\Controllers\GatewayController@destroy');

        //Server Details
        Route::get('server/{id?}', 'Server\Http\Controllers\ServerController@index');
        Route::post('server', 'Server\Http\Controllers\ServerController@store');
        Route::put('server/{id}', 'Server\Http\Controllers\ServerController@update');
        Route::delete('server/{id}', 'Server\Http\Controllers\ServerController@destroy');

        //Server List to be shown in user create page
        Route::get('serverlist', 'Server\Http\Controllers\ServerController@serverlist');

        //logout
        Route::post('logout', 'Users\Http\Controllers\UserController@logout');
        
        //Set global alert for Overall Monitoring
       Route::post('setglobalalertom', 'Users\Http\Controllers\UserController@setGlobalAlertOm');
       Route::get('getglobalalertom', 'Users\Http\Controllers\UserController@getGlobalAlertOm');
       Route::delete('deleteglobalalertom', 'Users\Http\Controllers\UserController@deleteGlobalAlertOm');
       
         //Set Bo Alert
       Route::post('saveboalert', 'Users\Http\Controllers\UserController@setBoAlert');
       Route::get('getboalert', 'Users\Http\Controllers\UserController@getBoAlert');
       Route::delete('deleteboalert', 'Users\Http\Controllers\UserController@deleteBoAlert');
       
       //User Trade ALert
       Route::get('usertrade/{id?}', 'Alert\Http\Controllers\AlertController@getTradeAlert');
       Route::post('saveusertrade', 'Alert\Http\Controllers\AlertController@saveUserTrade');
       Route::put('updateusertrade/{id}', 'Alert\Http\Controllers\AlertController@updateUserTrade');
       Route::delete('deleteusertrade/{id}', 'Alert\Http\Controllers\AlertController@deleteUserTrade');
       
       //Last Trade
         //Fox APi
       Route::get('lasttrade/{id?}', 'Alert\Http\Controllers\LastTradeController@getLastTrade');
       Route::put('updatelasttrade/{id}', 'Alert\Http\Controllers\LastTradeController@updateLastTrade');
         //Witelabel create
       Route::post('createwhitelabel', 'Alert\Http\Controllers\LastTradeController@createWhitelabel');
       Route::put('updatewhitelabel/{id}', 'Alert\Http\Controllers\LastTradeController@updateWhitelabel');
       Route::delete('deletewhitelabel/{id}', 'Alert\Http\Controllers\LastTradeController@deleteWhitelabel');
       
       
       //Trade Group
       Route::post('createtradegroup', 'Alert\Http\Controllers\ReportGroupController@saveGroup');
       Route::put('updatetradegroup', 'Alert\Http\Controllers\ReportGroupController@updateGroup');
       Route::get('gettradegrouplist/{id?}', 'Alert\Http\Controllers\ReportGroupController@getTradeGroupList');
       Route::delete('deletetradegrouplist', 'Alert\Http\Controllers\ReportGroupController@deleteTradeGroupList');
       
       
    });
});
