<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Common\Common;

class auditlog extends Model
{

    protected $fillable = [
        'server', 'logname', 'date', 'condition'
    ];
    protected $table = 'auditlog';
    public $timestamps = false;

    /**
     * Save Audit log report
     * 
     * 
     * @param type $server_name
     * @param type $request
     * @return boolean
     */
    public function saveAuditLog($server_name, $request)
    {   
        $serverid = common::getServerId($server_name);
        $this->server = $serverid;
        $this->logname = $request->input('logname');
        $this->date = $request->input('date');
        $this->condition = $request->input('condition');

        try {
            $this->save();
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    /**
     * Get Auditlog record
     * 
     * 
     * @param type $server_name
     * @param type $request
     * @return type mix(e.g array and boolean)
     */
    public function getAuditLog($server_name, $request)
    {

        $serverid = common::getServerId($server_name);
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $logname = $request->input('logname');
        $cond = $request->input('condition');

        $query = $this->select('*')
                ->where('server', $serverid)
                ->whereBetween('date', [$start_date, $end_date]);
        

        if ($logname) {
            $query->where('logname', '=', $logname);
        }

        if ($cond) {
            $query->where('condition', 'like', "%$cond%");
        }


        try {
            $result = array_map(function($v) {
                return [
                    'server' => common::getServerName($v['server']),
                    'logname' => $v['logname'],
                    'date' => $v['date'],
                    'condition' => $v['condition'],
                ];
            }, $query->get()->toArray());
        }
        catch (\Exception $exc) {
            return false;
        }

        return array('data' => $result);
    }

    

}
