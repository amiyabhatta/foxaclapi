<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use DB;
use Fox\Models\ReportGroup;
use Fox\Common\Common;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        $this->app['validator']->extend('only_numeric', function ($attribute, $value, $parameters, $validator) {
            $login = explode(',', rtrim($value, ','));
            foreach ($login as $chkNumericLogin) {
                if (!is_numeric($chkNumericLogin) || $chkNumericLogin <= 0) {
                    return false;
                }
            }
            return true;
        });

        //Check login
        $this->app['validator']->extend('check_id', function ($attribute, $value, $parameters, $validator) {
            
            $servermgrId = common::serverManagerId();
            $serverid = common::getServerId($servermgrId['server_name']);
            
            $login = common::getUserid($servermgrId['login']);
            
            $wl = DB::table('report_group')->where('id', '=', $value)
                                          ->where('login_mgr','=',$login)
                                          ->where('server','=',$serverid)
                                          ->get();
           
            
            if (count($wl)) {
                return true;
            }
            return false;
        });

        //Login should be unique
        $this->app['validator']->extend('login_unique', function ($attribute, $value, $parameters, $validator) {
            $login = explode(',', rtrim($value, ','));
            $servermgrId = common::serverManagerId();
            $serverid = common::getServerId($servermgrId['server_name']);
            
            $userId = common::getUserid($servermgrId['login']);
            foreach ($login as $chkNumericLogin) {
                //check login is already saved or not
                
                $chckLogin = DB::table('trade_alertusers')->where('login', '=', $chkNumericLogin)
                                          ->where('login_manager_id','=',$userId)
                                          ->where('server','=',$serverid)
                                          ->get();
                
                if (!is_numeric($chkNumericLogin) || $chkNumericLogin <= 0 || count($chckLogin) > 0) {
                    return false;
                }
            }
            return true;
        });

        //Check valid Tab/permission 
        $this->app['validator']->extend('check_validtab', function ($attribute, $value, $parameters, $validator) {
            $permission = explode(',', rtrim($value, ','));
            foreach ($permission as $pemrissionName) {
                //check login is already saved or not
                $getPermId = DB::table('permissions')->where('name', '=', $pemrissionName)->get();

                if (count($getPermId) == 0) {
                    return false;
                }
            }
            return true;
        });

        //Valid ticket in trade alert discard   valid_ticket and should be unique
        $this->app['validator']->extend('valid_ticket', function ($attribute, $value, $parameters, $validator) {

            if (!is_numeric($value) || $value <= 0) {
                return false;
            }
            return true;
        });

        //Check valid ticket in Last trade whitelabel email alert
        $this->app['validator']->extend('check_valid_ticket', function ($attribute, $value, $parameters, $validator) {

            if (!is_numeric($value) || $value <= 0) {
                return false;
            }
            return true;
        });

        //Whitelable name should be unique 

        $this->app['validator']->extend('unique_whitelabel', function ($attribute, $value, $parameters, $validator) {
            list($id) = $parameters;

            //check wl should not add except this id
            $getPermId = DB::table('lasttrade_whitelabels')->where('WhiteLabels', '=', $value)
                    ->where('id', '<>', $parameters[0])
                    ->get();

            if (count($getPermId)) {
                return false;
            }
            return true;
        });
        
        //check whitelabel name is vaialble or not 
        $this->app['validator']->extend('check_valid_whitelabel', function ($attribute, $value, $parameters, $validator) {
            
            $getWlName = DB::table('lasttrade_whitelabels')->where('WhiteLabels', '=', $value)
                    ->get();

            if (count($getWlName)) {
                return true;
            }
            return false;
        });
        
        //Group should be unique per server and mangerid
        $this->app['validator']->extend('unique_group', function ($attribute, $value, $parameters, $validator) {
            
            $groupName = $value;
            $servermgrId = common::serverManagerId();
            $serverid = common::getServerId($servermgrId['server_name']);
            $userId = common::getUserid($servermgrId['login']);
            
            $getWlName = DB::table('report_group')->where('login_mgr', '=', $userId)->where('server', '=', $serverid)->where('group_name', '=', $groupName)
                         ->get();

            
            if (!count($getWlName)) {
                return true;
            }
            return false;
            
            
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
