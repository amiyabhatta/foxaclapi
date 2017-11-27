<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function dump;

class lasttrade_whitelabels_emails_alert extends Model {

    protected $fillable = [
        'ticket', 'whitelabels'
    ];
    protected $table = 'lasttrade_whitelabels_emails_alert';
    public $timestamps = false;

    /**
     * Get Last trade white label email alert
     * 
     * 
     * @param type $request
     * @return boolean|string
     */
    public function getLastTradeWlEmailAlert($request) {

        
        if ($request->input('ticket')) {
            $ticket = rtrim($request->input('ticket'), ',');
            $arrId = explode(',', $ticket);

            $query = $this->whereIn('ticket', $arrId);
            try {
                $result = array_map(function($v) {
                    return [
                        'ticket' => $v['ticket'],
                        'whitelabels' => $v['whitelabels'],
                    ];
                }, $query->get()->toArray());
            } catch (\Exception $exc) {
                return false;
            }
            return array('data' => ($result ? $result : 'No record found'));
        }
    }

    /**
     * Save data for Last trade white label email alert
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function saveLastTradeWlEmailAlert($request) {
        try {
            $this->ticket = $request->input("ticket");
            $this->whitelabels = $request->input("whitelabel");
            
            $this->save();
        } catch (\Exception $exc) {
            return false;
        }
        return true;
    }

}
