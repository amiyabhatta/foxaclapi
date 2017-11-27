<?php

namespace Fox\Common;

use Fox\common\Base;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
Use Fox\Models\UserHasRole;
use DB;

class Common extends Base
{

    /**
     * 
     * @param type $loggedinUserId
     * @return type
     */
    public static function checkuserrole($loggedinUserId)
    {

        $results = UserHasRole::select('roles_id')
                ->where('user_id', $loggedinUserId)
                ->get();
        return $results[0]->roles_id;
    }

    /**
     * Get Current Language
     * 
     * @return type
     */
    public static function getLang()
    {
        return app()->getLocale();
    }

    public static function checkRole()
    {
        $userRole = new UserHasRole;
        $userinfo = JWTAuth::parseToken()->authenticate();

        $role = $userRole->select('role_slug')
                        ->leftjoin('roles', 'roles.id', '=', 'users_has_roles.roles_id')
                        ->where('users_has_roles.user_id', '=', $userinfo->id)->first();

        if (count($role)) {
            return $role->role_slug;
        }
        return false;
    }

    //Get server name and manager id from Token
    public static function serverManagerId()
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $userinfo = JWTAuth::parseToken()->authenticate();

        return array('server_name' => $payload->get('server_name'), 'login' => $userinfo->manager_id);
    }
    
    //Get serverId from servername 
    public static function getServerId($serverName = NULL){
        $serverId = '';
        if($serverName){
            $serverId =  DB::select( DB::raw("SELECT id FROM serverlist WHERE servername = '$serverName'") );
            return $serverId[0]->id;
        }
        return $serverId;
    }
    
    //Get serverId from servername 
    public static function getServerName($serverId = NULL){
        $serverName = '';
        if((int) $serverId){
            $serverName =  DB::select( DB::raw("SELECT servername FROM serverlist WHERE id =".$serverId) );
            return $serverName[0]->servername;
        }
        return $serverName;
    }

    //get user id from login mnager
    public static function getUserid($loginmgr = NULL){
        $userId = '';
        if($loginmgr){
            $userId =  DB::select( DB::raw("SELECT id FROM users WHERE manager_id =".$loginmgr) );
            return $userId[0]->id;
        }
        return $userId;
    }
    
    //get mangerid from login userid
    public static function getloginMgr($userId = NULL){
        
        $loginmgr = '';
        if($userId){
            $loginmgr =  DB::select( DB::raw("SELECT manager_id FROM users WHERE id=".$userId) );
            return $loginmgr[0]->manager_id;
        }
        return $loginmgr;
    }
}
