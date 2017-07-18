<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasRole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'role_slug'
    ];
    
    protected $table = 'users_has_roles';
    
    /*
     * return string
     */
    public function getUserRole($userid)
    {

        $user_role = $this->select('roles_id')
                        ->where('user_id', '=', $userid)->get();

        if (count($user_role)) {
            return $user_role[0]->roles_id;
        }
        else {
            //if no role the return user
            return 4;
        }
    }
}
