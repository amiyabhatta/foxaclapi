<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\roleHasPermission;
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
     * Add a resource.
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
     * Add a resource.
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
     * Add a resource.
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
            $perm_id = $request->segment(4);
            DB::transaction(function () use ($perm_id) {
                $this->where('id', '=', $perm_id)->delete();

                roleHasPermission::where('permissions_id', '=', $perm_id)->delete();
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
    public function getAllPermission($limit, $id = NULL)
    {
        $query = $this->select('id', 'name', 'user_type');
        if ($id) {
            $query->where('id', '=', $id);
        }
        $result = $query->paginate($limit);

        return $result;
    }

}
