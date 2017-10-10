<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\UserHasRole;
use Illuminate\Support\Facades\DB;
use Fox\Models\RoleHasPermission;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'role_slug'
    ];

    /**
     * Get All role
     * 
     * 
     * @param type $limit
     * @param type $id
     * @return type array
     */
    public function getAllRoles($limit, $roleId = NULL)
    {

        $query = $this->select('id', 'role', 'role_slug');
        
        if ($roleId) {
            $query->where('id', '=', $roleId);
        }

        $query->where('role_slug', '!=', 'super_administrator');
        $query->orderBy('id', 'desc');
        $result = $query->paginate($limit);
        return $result;
    }

    /**
     * Add role
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function addRole($request)
    {

        try {
            $this->role = $request->input('role');
            $this->role_slug = preg_replace('/\s+/', '_', strtolower($request->input('role')));
            if (!$this->save()) {
                return false;
            }
        }
        catch (\Exception $exc) {
            return $exc;
        }
        return true;
    }

    /**
     * update role
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function updateRole($request)
    {

        $role = $this->find($request->segment(4));

        if (!$role) {
            return false;
        }

        if ($request->input('role')) {
            $role->role = $request->input('role');
            $role->role_slug = preg_replace('/\s+/', '_', strtolower($request->input('role')));
            if (!$role->save()) {
                return false;
            }
        }
        return true;
    }

    /**
     * delete role
     * 
     * @param type $request
     * @return boolean
     */
    public function deleteRole($request)
    {

        $role = $this->find($request->segment(4));

        if (!$role) {
            return false;
        }
        try {
            $roleId = $request->segment(4);
            DB::transaction(function () use ($roleId) {
                $this->where('id', '=', $roleId)->delete();

                UserHasRole::where('roles_id', '=', $roleId)->delete();
            });
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Assign Role role to permission
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function assignRoletoPerm($request)
    {

        $roleHasPerm = new RoleHasPermission;

        //Update 
        $permId = $roleHasPerm->select('*')
                ->where('role_id', '=', $request->segment(4))
                ->where('permissions_id', '=', $request->input('permission_id'))
                ->get();

        if (count($permId)) {
            try {
                $roleHasPerm->where('role_id', $request->segment(4))
                        ->where('permissions_id', $request->input('permission_id'))
                        ->update(['action' => $request->input('action')]);

                return true;
            }
            catch (\Exception $e) {
                return false;
            }
        }

        //Insert
        $checkRole = $this->find($request->segment(4));
        if ($checkRole) {
            $roleHasPerm->role_id = $request->segment(4);
            $roleHasPerm->permissions_id = $request->input('permission_id');
            $roleHasPerm->action = $request->input('action');

            try {
                $roleHasPerm->save();
                return true;
            }
            catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

}
