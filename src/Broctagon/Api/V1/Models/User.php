<?php

namespace Fox\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Fox\Models\Role;
use Illuminate\Support\Facades\DB;
use Fox\Models\UserHasRole;
use Fox\Models\UserserverAccess;
use Fox\Models\Mt4gateway;
use Fox\Common\Common;
use Fox\Models\Mailsetting;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'activate_status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get All users
     * 
     * @param type $limit
     * @param type $id
     * @return type array
     */
    public function getAllUsers($limit, $userId)
    {
        $query = $this->select('*')
                ->where('email', '!=', 'james@gmail.com')
                ->where('activate_status', '=', 1)
                ->orderBy('id', 'desc');
        //->paginate($limit);

        if ($userId) {
            $query->where('id', '=', $userId);
        }
        $result = $query->paginate($limit);
        return $result;
    }

    /**
     * Create user
     * 
     * @param type $request
     * @return boolean
     */
    public function addUser($request)
    {

        try {
            DB::transaction(function() use ($request) {

                $this->name = $request->input('user_name');
                $this->manager_id = $request->input('user_manager_id');
                $this->email = $request->input('user_email');
                $this->password = bcrypt($request->input('password'));
                $this->activate_status = 1;
                $this->save();
                //Inserting into relationships(pivot table UserserverAccess)                        


                $serverId = $request->input('server_id');

                $ids = explode(',', $serverId);

                foreach ($ids as $serveIds) {
                    $userserver = new UserserverAccess();
                    $userserver->user_id = $this->id;
                    $userserver->server_id = $serveIds;
                    $userserver->save();
                }
            });
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

    /**
     * Get Role id 
     * 
     * @return int
     */
    public function getRoleId()
    {

        $roleId = Role::select('id')
                        ->where('role_slug', '=', 'user')->first();
        if (count($roleId)) {
            return $roleId->id;
        }
        return 0;
    }

    /**
     * Update user filed
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function updateUser($request)
    {
        
        $user = $this->find($request->segment(4));

        if (!$user) {
            return false;
        }

        if ($request->input('user_name')) {
            $user->name = $request->input('user_name');
        }
        $user->email = $request->input('user_email');
        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->manager_id = $request->input('user_manager_id');

        $user->activate_status = 1;

//        if ($request->input('server_id')) {
//            //assign or unassing server to user
//            $this->serverToUser($request->input('server_id'),$request);
//        } 
//        else {
//            $userserver = new UserserverAccess();
//            $userserver->where('user_id', '=', $request->segment(4))->delete();
//        }
        
        
        //assign or unassing server to user
        $assignStatus = $this->serverToUser($request->input('server_id'),$request);
       
        if (!$user->save() || (!$assignStatus)) {
            return false;
        }
        return true;
    }

    /**
     * Delete user (By changing status)
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function deleteUser($request)
    {

        $user = $this->find($request->segment(4));

        try {
            if (!$user) {
                return false;
            }
            $user->activate_status = 0;
            $user->save();
            return true;
        } catch (\Exception $exc) {
            return 'error';
        }
    }

    /**
     * Assign Role to user
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function assignRoleToUser($request)
    {

        $userHasRole = new UserHasRole;
        //Update 
        $roleId = $userHasRole->select('*')
                ->where('user_id', '=', $request->segment(4))
                ->get();

        if (count($roleId)) {
            try {
                $userHasRole->where('user_id', $request->segment(4))
                        ->update(['action' => $request->input('action'), 'roles_id' => $request->input('role_id')]);

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        //Insert
        $checkUser = $this->find($request->segment(4));

        if ($checkUser) {
            $userHasRole->user_id = $request->segment(4);
            $userHasRole->roles_id = $request->input('role_id');
            $userHasRole->action = $request->input('action');
            try {
                $userHasRole->save();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Get server details assign to user
     * 
     * 
     * @param type $user_id
     * @param type $server_name
     * @return type
     */
    public function getUserServerDetails($userId, $serverName = null)
    {

        $userServerAccess = new UserserverAccess;
        $result = $userServerAccess->select('serverlist.servername', 'serverlist.ipaddress', 'serverlist.username', 'serverlist.password', 'serverlist.databasename', 'serverlist.GatewayID')
                ->leftjoin('users', 'user_server_access.user_id', '=', 'users.id')
                ->leftjoin('serverlist', 'serverlist.id', '=', 'user_server_access.server_id')
                ->where('user_server_access.user_id', '=', $userId)
                ->where('serverlist.servername', '=', $serverName)
                ->get();

        $serverArray = [];
        $increament = 0;
        foreach ($result as $serverDetails) {
            $serverArray[$increament]['server_name'] = $serverDetails['servername'];
            $serverArray[$increament]['server_ip'] = $serverDetails['ipaddress'];
            $serverArray[$increament]['server_username'] = $serverDetails['username'];
            $serverArray[$increament]['server_password'] = $serverDetails['password'];
            $serverArray[$increament]['server_db'] = $serverDetails['databasename'];
            $serverArray[$increament]['server_gw'] = $serverDetails['GatewayID'];
            $increament++;
        }
        return $serverArray;
    }

    /**
     * Get list of permission assign to user
     * 
     * @param type $user_id
     * @return aray
     */
    public function getUserPermissionDetails($userId)
    {

        $result = $this->select('permissions.name')
                ->leftjoin('users_has_roles', 'users.id', '=', 'users_has_roles.user_id')
                ->leftjoin('roles_has_permissions', 'users_has_roles.roles_id', '=', 'roles_has_permissions.role_id')
                ->leftjoin('permissions', 'roles_has_permissions.permissions_id', '=', 'permissions.id')
                ->where('users.id', '=', $userId)
                ->where('roles_has_permissions.action', '=', 1)
                ->get();

        $permissionArray = [];
        $increament = 0;
        foreach ($result as $permissionDetails) {
            //$permission_array[$i]['tab_name'] = $permissiondetails['name'];
            $permissionArray[$increament][$permissionDetails['name']] = 1;
            $increament++;
        }
        return $permissionArray;
    }

    /**
     * Get gateway details assign to a particular server
     * 
     * @param type $server_name
     * @return array
     */
    public function getUserGatewayDetails($serverName)
    {
        $gwModel = new Mt4gateway();
        $gwResult = $gwModel->select('gateway_name', 'host', 'port', 'master_password', 'mt4gateway.username')
                        ->join('serverlist', 'serverlist.GatewayID', '=', 'mt4gateway.id')
                        ->where('serverlist.servername', '=', $serverName)->first();
        $gwDetails = [];
        if ($gwResult) {
            $gwDetails[0]['gateway_name'] = $gwResult->gateway_name;
            $gwDetails[0]['host'] = $gwResult->host;
            $gwDetails[0]['port'] = $gwResult->port;
            $gwDetails[0]['master_password'] = $gwResult->master_password;
            $gwDetails[0]['username'] = $gwResult->username;
            return $gwDetails;
        }
        return $gwDetails;
    }

    /**
     * Get DB details
     * 
     * @return type array
     */
    public function getDbDetails()
    {

        $dbDetials = [];
        $dbDetials[0]['host'] = env('DB_HOST', false);
        $dbDetials[0]['db_name'] = env('DB_DATABASE', false);
        $dbDetials[0]['db_username'] = env('DB_USERNAME', false);
        $dbDetials[0]['db_password'] = env('DB_PASSWORD', false);

        return $dbDetials;
    }

    /**
     * update|change password
     * 
     * 
     * @param type $request
     * @param type $servername
     * @param type $loginmgr
     * @return boolean
     */
    public function passwordUpdate($request, $loginMgr)
    {

        $newPassword = bcrypt($request->input('new_password'));

        try {
            $this->where('manager_id', $loginMgr)
                    ->update(['password' => $newPassword]);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Mail Setting for login user
     * 
     * @param type $loginid
     * @param type $server
     * @return array
     */
    public function getMailSetting($loginId, $server)
    {
        //get manager id
        $loginmgr = $this->select('manager_id')
                        ->where('id', $loginId)->first();


        $mailsetting = new Mailsetting();
        $getmailsetting = $mailsetting->select('login', 'server', 'smtpserver', 'mailfrom', 'mailto', 'password', 'port', 'ssl', 'enabled')
                        ->where('login', '=', $loginmgr->manager_id)
                        ->where('server', '=', $server)->get()->toArray();

        $mailsettingArray = [];
        if ($getmailsetting) {
            $mailsettingArray[0]['login'] = $getmailsetting[0]['login'];
            $mailsettingArray[0]['server'] = $getmailsetting[0]['server'];
            $mailsettingArray[0]['smtpserver'] = $getmailsetting[0]['smtpserver'];
            $mailsettingArray[0]['mailfrom'] = $getmailsetting[0]['mailfrom'];
            $mailsettingArray[0]['mailto'] = $getmailsetting[0]['mailto'];
            $mailsettingArray[0]['password'] = $getmailsetting[0]['password'];
            $mailsettingArray[0]['port'] = $getmailsetting[0]['port'];
            $mailsettingArray[0]['ssl'] = $getmailsetting[0]['ssl'];
            $mailsettingArray[0]['enabled'] = $getmailsetting[0]['enabled'];
            return $mailsettingArray;
        }

        return $mailsettingArray;
    }

    /**
     * Get server details assign to user
     * 
     * 
     * @param type $userId
     * @param type $serverName
     * @return boolean
     */
    public function checkServerAssign($userId, $serverName)
    {
        $userServerAccess = new UserserverAccess;
        $result = $userServerAccess->select('serverlist.servername')
                ->leftjoin('users', 'user_server_access.user_id', '=', 'users.id')
                ->leftjoin('serverlist', 'serverlist.id', '=', 'user_server_access.server_id')
                ->where('user_server_access.user_id', '=', $userId)
                ->where('serverlist.servername', '=', $serverName)
                ->get();
        if (count($result)) {
            return true;
        }
        return false;
    }

    public function serverToUser($serverId, $request)
    {

        try {
            $ids = explode(',', $serverId);
            
            foreach ($ids as $serveIds) {
                $userserver = new UserserverAccess();
                $checkAlreadyExist = $userserver->where('user_id', '=', $request->segment(4))
                                ->where('server_id', '=', $serveIds)->first();

                if (!count($checkAlreadyExist)) {
                    $userserver->user_id = $request->segment(4);
                    $userserver->server_id = $serveIds;
                    $userserver->save();
                }
            }
            //Do unselect
            $checkAlreadyAssign = $userserver->where('user_id', '=', $request->segment(4))->get();
            $assignServer = [];
            $increament = 0;
            foreach ($checkAlreadyAssign as $checkAlreadyAssign) {
                $assignServer[$increament] = $checkAlreadyAssign->server_id;
                $increament++;
            }
            $result = array_diff($assignServer, $ids);


            if (count($result)) {
                foreach ($result as $serverId) {
                    $userserver->where('user_id', '=', $request->segment(4))
                            ->where('server_id', '=', $serverId)
                            ->delete();
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
