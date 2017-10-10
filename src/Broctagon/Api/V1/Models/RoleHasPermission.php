<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
     protected $fillable = [
        'role_id','permissions_id'
    ];
     
  protected $table = 'roles_has_permissions';
}
