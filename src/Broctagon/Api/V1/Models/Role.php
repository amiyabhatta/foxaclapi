<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\UserHasRole;
use Illuminate\Support\Facades\DB;
use Fox\Models\roleHasPermission;

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

    public function getAllRoles($limit)
    {

        return $this->select('id', 'role', 'role_slug')
                        ->where('role_slug', '!=', 'super_administrator')
                        ->paginate($limit);
    }

    /**
     * Add a resource.
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
     * Add a resource.
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
     * Add a resource.
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
            $role_id = $request->segment(4);
            DB::transaction(function () use ($role_id) {
                $this->where('id', '=', $role_id)->delete();

                UserHasRole::where('roles_id', '=', $role_id)->delete();
            });
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add a resource.
     * @param type $request
     * @return boolean
     */
    public function assignRoletoPerm($request)
    {
        
        $role_has_perm = new roleHasPermission;

        //Update 
        $perm_id = $role_has_perm->select('*')
                ->where('role_id', '=', $request->segment(4))
                ->where('permissions_id', '=', $request->input('permission_id'))
                ->get();

        if (count($perm_id)) {
            try {
                $role_has_perm->where('role_id', $request->segment(4))
                        ->where('permissions_id', $request->input('permission_id'))
                        ->update(['action' => $request->input('action')]);

                return true;
            }
            catch (\Exception $e) {
                return false;
            }
        }

        //Insert
        $check_role = $this->find($request->segment(4));        
        if($check_role){
        $role_has_perm->role_id = $request->segment(4);
        $role_has_perm->permissions_id = $request->input('permission_id');
        $role_has_perm->action = $request->input('action');

        try {
            $role_has_perm->save();
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
        }
        
        return false;
    }

}
