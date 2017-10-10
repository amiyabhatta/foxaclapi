<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class TradeAlertDiscard extends Model
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
     * @param type $loginMgr
     * @return boolean
     */
    public function saveTardeAlertDiscrd($request, $loginMgr)
    {
        try {
            $this->Ticket = $request->input('ticket');
            $this->Addedon = Carbon::now();
            $this->login_mgr = $loginMgr;
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
     * @param type $loginMgr
     * @return array|String
     */
    public function getTardeAlertDiscrd($request, $loginMgr)
    {

        $date = $request->input('addedon');
        
        try {
            $query = DB::select(DB::raw("SELECT * FROM trade_alert_discard WHERE Addedon >= date('$date') AND login_mgr = $loginMgr"));
            $result = [];
            $indexresult = 0;
            foreach ($query as $queryResult) {
                $result[$indexresult]['Ticket'] = $queryResult->Ticket;
                $result[$indexresult]['Login'] = $queryResult->login_mgr;
                $result[$indexresult]['Addedon'] = $queryResult->Addedon;
                $indexresult++;
            }
        } catch (\Exception $exc) {
            return false;
        }

        return array('data' => ($result ? $result : 'No record found'));
    }

}
