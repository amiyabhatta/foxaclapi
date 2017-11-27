<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Fox\Common\Common;

class trade_alert_discard extends Model
{

    protected $fillable = [
        'Ticket', 'Addedon', 'login_mgr'
    ];
    protected $table = 'trade_alert_discard';
    public $timestamps = false;

    /**
     * Save data for trade alert discard
     * 
     * 
     * @param type $request
     * @param type $login_mgr
     * @return boolean
     */
    public function saveTardeAlertDiscrd($request, $login_mgr)
    {
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

    /**
     * Get Trade alert discard data
     * 
     * @param type $request
     * @param type $login_mgr
     * @return boolean
     */
    public function getTardeAlertDiscrd($request, $login_mgr)
    {

        $date = $request->input('addedon');
        
        try {
            $query = DB::select(DB::raw("SELECT * FROM trade_alert_discard WHERE Addedon >= date('$date') AND login_mgr = $login_mgr"));
            $result = [];
            $i = 0;
            foreach ($query as $queryResult) {
                $result[$i]['Ticket'] = $queryResult->Ticket;
                //$result[$i]['Login'] = $queryResult->login_mgr;
                $result[$i]['Login'] = common::getloginMgr($queryResult->login_mgr);
                $result[$i]['Addedon'] = $queryResult->Addedon;
                $i++;
            }
        } catch (\Exception $exc) {
            return false;
        }

        return array('data' => ($result ? $result : 'No record found'));
    }

}
