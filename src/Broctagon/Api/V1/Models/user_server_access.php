<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class user_server_access extends Model
{
    protected $fillable = [
        'user_id','server_id'
    ];
    
    protected $table = 'user_server_access';
}
