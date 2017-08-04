<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class auditlog extends Model
{

    protected $fillable = [
        'server', 'logname', 'date', 'condition'
    ];
    protected $table = 'auditlog';
    public $timestamps = false;

    public function saveAuditLog($server_name, $request)
    {
        $this->server = $server_name;
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

    public function getAuditLog($server_name, $request)
    {

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $logname = $request->input('logname');
        $cond = $request->input('condition');

        $query = $this->select('*')
                ->where('server', $server_name)
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
                    'server' => $v['server'],
                    'logname' => $v['logname'],
                    'date' => $v['date'],
                    'condition' => $v['condition'],
                ];
            }, $query->get()->toArray());
        }
        catch (\Exception $exc) {
            dd($exc);
        }

        return array('data' => $result);
    }

    

}
