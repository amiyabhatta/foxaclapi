<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\RoleHasPermission;
use Illuminate\Support\Facades\DB;

class Permissions extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_type'
    ];

    /**
     * save permission 
     * 
     * @param type $request
     * @return boolean
     */
    public function addPermission($request)
    {
        try {
            $this->name = $request->input('permission');
            $this->user_type = $request->input('user_type');
            if (!$this->save()) {
                return false;
            }
            return true;
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    /**
     * Update permission 
     * 
     * @param type $request
     * @return boolean
     */
    public function updatePermission($request)
    {

        $permission = $this->find($request->segment(4));

        if (!$permission) {
            return false;
        }


        $permission->name = $request->input('permission');
        $permission->user_type = $request->input('user_type');
        try {
            $permission->save();
        }
        catch (\Exception $exc) {
            return false;
        }


        return true;
    }

    /**
     * Delete permission by id
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function deletePermission($request)
    {

        $perm = $this->find($request->segment(4));

        if (!$perm) {
            return false;
        }
        try {
            $permId = $request->segment(4);
            DB::transaction(function () use ($permId) {
                $this->where('id', '=', $permId)->delete();

                RoleHasPermission::where('permissions_id', '=', $permId)->delete();
            });
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all permission 
     * 
     * 
     * @param type $limit
     * @param type $id
     * @return type array
     */
    public function getAllPermission($limit, $permissionId = NULL)
    {
        $query = $this->select('id', 'name', 'user_type')
                      ->orderBy('id', 'desc');
        
        if ($permissionId) {
            $query->where('id', '=', $permissionId);
        }
        
        $result = $query->paginate($limit);

        return $result;
    }

}
