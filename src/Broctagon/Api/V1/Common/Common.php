<?php

namespace Fox\Common;

use Fox\common\Base;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
Use Fox\Models\UserHasRole;

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

}
