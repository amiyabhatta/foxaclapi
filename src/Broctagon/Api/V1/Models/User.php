<?php

namespace Fox\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Fox\Models\Role;
use Illuminate\Support\Facades\DB;
use Fox\Models\UserHasRole;
use Fox\Models\user_server_access;
use Fox\Models\Mt4gateway;
use Fox\Common\Common;
use Fox\Models\Mailsetting;

class User extends Authenticatable {

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

    public function getAllUsers($limit, $id) {
        $query = $this->select('*')
                ->where('email', '!=', 'james@gmail.com')
                ->where('activate_status', '=', 1)
                ->orderBy('id', 'desc');
        //->paginate($limit);

        if ($id) {
            $query->where('id', '=', $id);
        }
        $result = $query->paginate($limit);
        return $result;
    }

    /**
     * Add a resource.
     * @param type $request
     * @return boolean
     */
    public function addUser($request) {

        try {
            $data = DB::transaction(function() use ($request) {

                        $this->name = $request->input('user_name');
                        $this->manager_id = $request->input('user_manager_id');
                        $this->email = $request->input('user_email');
                        $this->password = bcrypt($request->input('user_password'));
                        $this->activate_status = 1;
                        $this->save();
                        //Inserting into relationships(pivot table user_server_access)                        


                        $server_id = $request->input('server_id');

                        $ids = explode(',', $server_id);

                        foreach ($ids as $serve_ids) {
                            $userserver = new user_server_access();
                            $userserver->user_id = $this->id;
                            $userserver->server_id = $serve_ids;
                            $userserver->save();
                        }
                    });
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

    public function getRoleId() {

        $role_id = Role::select('id')
                        ->where('role_slug', '=', 'user')->first();
        if (count($role_id)) {
            return $role_id->id;
        }
        return 0;
    }

    /**
     * Add a resource.
     * @param type $request
     * @return boolean
     */
    public function updateUser($request) {
        $user = $this->find($request->segment(4));

        if (!$user) {
            return false;
        }

        if ($request->input('user_name')) {
            $user->name = $request->input('user_name');
        }
        $user->email = $request->input('user_email');
        if ($request->input('user_password')) {
            $user->password = bcrypt($request->input('user_password'));
        }
        $user->manager_id = $request->input('user_manager_id');

        $user->activate_status = 1;

        if ($request->input('server_id')) {

            $server_id = $request->input('server_id');

            $ids = explode(',', $server_id);

            foreach ($ids as $serve_ids) {
                $userserver = new user_server_access();
//                            $userserver->user_id = $this->id;
//                            $userserver->server_id = $serve_ids;
//                            $userserver->save();
                $check_already_exist = $userserver->where('user_id', '=', $request->segment(4))
                                ->where('server_id', '=', $serve_ids)->first();
                if (!count($check_already_exist)) {
                    $userserver->user_id = $request->segment(4);
                    $userserver->server_id = $serve_ids;
                    $userserver->save();
                }
            }
            //Do unselect
            $check_already_assign = $userserver->where('user_id', '=', $request->segment(4))->get();
            $assign_server = [];
            $i = 0;
            foreach ($check_already_assign as $check_already_assign) {
                $assign_server[$i] = $check_already_assign->server_id;
                $i++;
            }
            $result = array_diff($assign_server, $ids);
            //$result = array_diff($assign_server, $ids);

            if (count($result)) {
                foreach ($result as $server_id) {
                    $userserver->where('user_id', '=', $request->segment(4))
                            ->where('server_id', '=', $server_id)
                            ->delete();
                }
            }
        } else {
            $userserver = new user_server_access();
            $userserver->where('user_id', '=', $request->segment(4))->delete();
        }


        if (!$user->save()) {
            return false;
        }
        return true;
    }

    /**
     * Add a resource.
     * @param type $request
     * @return boolean
     */
    public function deleteUser($request) {

        $user = $this->find($request->segment(4));

        if (!$user) {
            return false;
        } else {
            $user->activate_status = 0;
            try {
                $user->save();
            } catch (\Exception $exc) {
                return 'error';
            }
            return true;
        }
    }

    public function assignRoleToUser($request) {

        $user_has_role = new UserHasRole;

        //Update 
        $role_id = $user_has_role->select('*')
                ->where('user_id', '=', $request->segment(4))
                ->get();



        if (count($role_id)) {
            try {
                $user_has_role->where('user_id', $request->segment(4))
                        ->update(['action' => $request->input('action'), 'roles_id' => $request->input('role_id')]);

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        //Insert
        $check_user = $this->find($request->segment(4));



        if ($check_user) {
            $user_has_role->user_id = $request->segment(4);
            $user_has_role->roles_id = $request->input('role_id');
            $user_has_role->action = $request->input('action');


            try {
                $user_has_role->save();
                return true;
            } catch (\Exception $e) {
                dd($e);
                return false;
            }
        }

        return false;
    }

    public function getUserServerDetails($user_id, $server_name = null) {

        $user_server_access = new user_server_access;
        $result = $user_server_access->select('serverlist.servername', 'serverlist.ipaddress', 'serverlist.username', 'serverlist.password', 'serverlist.databasename', 'serverlist.GatewayID')
                ->leftjoin('users', 'user_server_access.user_id', '=', 'users.id')
                ->leftjoin('serverlist', 'serverlist.id', '=', 'user_server_access.server_id')
                ->where('user_server_access.user_id', '=', $user_id)
                ->where('serverlist.servername', '=', $server_name)
                ->get();
        $server_array = [];
        $i = 0;
        foreach ($result as $serverdetails) {
            $server_array[$i]['server_name'] = $serverdetails['servername'];
            $server_array[$i]['server_ip'] = $serverdetails['ipaddress'];
            $server_array[$i]['server_username'] = $serverdetails['username'];
            $server_array[$i]['server_password'] = $serverdetails['password'];
            $server_array[$i]['server_db'] = $serverdetails['databasename'];
            $server_array[$i]['server_gw'] = $serverdetails['GatewayID'];
            $i++;
        }
        return $server_array;
    }

    public function getUserPermissionDetails($user_id) {

        $result = $this->select('permissions.name')
                ->leftjoin('users_has_roles', 'users.id', '=', 'users_has_roles.user_id')
                ->leftjoin('roles_has_permissions', 'users_has_roles.roles_id', '=', 'roles_has_permissions.role_id')
                ->leftjoin('permissions', 'roles_has_permissions.permissions_id', '=', 'permissions.id')
                ->where('users.id', '=', $user_id)
                ->where('roles_has_permissions.action', '=', 1)
                ->get();

        $permission_array = [];
        $i = 0;
        foreach ($result as $permissiondetails) {
            //$permission_array[$i]['tab_name'] = $permissiondetails['name'];
            $permission_array[$i][$permissiondetails['name']] = 1;
            $i++;
        }
        return $permission_array;
    }

    public function getUserGatewayDetails($server_name) {
        $gw_model = new Mt4gateway();
        $gw_result = $gw_model->select('gateway_name', 'host', 'port', 'master_password', 'mt4gateway.username')
                        ->join('serverlist', 'serverlist.GatewayID', '=', 'mt4gateway.id')
                        ->where('serverlist.servername', '=', $server_name)->first();
        $gw_details = [];
        if ($gw_result) {
            $gw_details[0]['gateway_name'] = $gw_result->gateway_name;
            $gw_details[0]['host'] = $gw_result->host;
            $gw_details[0]['port'] = $gw_result->port;
            $gw_details[0]['master_password'] = $gw_result->master_password;
            $gw_details[0]['username'] = $gw_result->username;
        } else {
            $gw_details = [];
        }

        return $gw_details;
    }

    public function getDbDetails() {

        $db_detials = [];
        $db_detials[0]['host'] = env('DB_HOST', false);
        $db_detials[0]['db_name'] = env('DB_DATABASE', false);
        $db_detials[0]['db_username'] = env('DB_USERNAME', false);
        $db_detials[0]['db_password'] = env('DB_PASSWORD', false);

        return $db_detials;
    }

    //update password

    public function passwordUpdate($request, $servername, $loginmgr) {

        $newpassword = bcrypt($request->input('new_password'));

        try {
            $this->where('manager_id', $loginmgr)
                    ->update(['password' => $newpassword]);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    //Mail Setting for login user

    public function getMailSetting($loginid, $server) {
        //get manager id
        $loginmgr = $this->select('manager_id')
                        ->where('id', $loginid)->first();


        $mailsetting = new Mailsetting();
        $getmailsetting = $mailsetting->select('login', 'server', 'smtpserver', 'mailfrom', 'mailto', 'password', 'port', 'ssl', 'enabled')
                        ->where('login', '=', $loginmgr->manager_id)
                        ->where('server', '=', $server)->get()->toArray();

        $mailsetting = [];
        if ($getmailsetting) {
            $mailsetting[0]['login'] = $getmailsetting[0]['login'];
            $mailsetting[0]['server'] = $getmailsetting[0]['server'];
            $mailsetting[0]['smtpserver'] = $getmailsetting[0]['smtpserver'];
            $mailsetting[0]['mailfrom'] = $getmailsetting[0]['mailfrom'];
            $mailsetting[0]['mailto'] = $getmailsetting[0]['mailto'];
            $mailsetting[0]['password'] = $getmailsetting[0]['password'];
            $mailsetting[0]['port'] = $getmailsetting[0]['port'];
            $mailsetting[0]['ssl'] = $getmailsetting[0]['ssl'];
            $mailsetting[0]['enabled'] = $getmailsetting[0]['enabled'];
        } else {
            $mailsetting = [];
        }

        return $mailsetting;
    }

    public function checkServerAssign($userId, $serverName) {
        $user_server_access = new user_server_access;
        $result = $user_server_access->select('serverlist.servername')
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

}
