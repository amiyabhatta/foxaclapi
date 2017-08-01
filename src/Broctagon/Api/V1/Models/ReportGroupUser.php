<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class ReportGroupUser extends Model
{
    protected $fillable = [
        'login', 'report_group_id'
    ];
    protected $table = 'report_gruoptousers';
    public $timestamps = false;
}
