<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class trade_alert_discard extends Model {

    protected $fillable = [
        'Ticket', 'Addedon', 'login_mgr'
    ];
    protected $table = 'trade_alert_discard';
    public $timestamps = false;

    public function saveTardeAlertDiscrd($request, $login_mgr) {
        //$mytime = Carbon::now();

        try {
            $this->Ticket = $request->input('ticket');
            $this->Addedon = Carbon::now();
            $this->login_mgr = $login_mgr;
            $this->save();
        } catch (\Exception $exc) {
            return false;
        }

        return true;
    }

    public function getTardeAlertDiscrd($request, $login_mgr) {

        $query = $this->where('Addedon', '>=', $request->input('addedon'))
                ->where('login_mgr', '=', $login_mgr);

        try {
            $result = array_map(function($v) {
                return [
                    'Ticket' => $v['Ticket'],
                    'Login' => $v['login_mgr'],
                    'Addedon' => $v['Addedon'],
                ];
            }, $query->get()->toArray());
        } catch (\Exception $exc) {
            return false;
        }

        return array('data' => $result);
    }

}
