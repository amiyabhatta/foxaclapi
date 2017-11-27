<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Fox\Common\Common;

class Usertrade extends Model {

    //trade_alertusers

    protected $fillable = [
        'login', 'login_manager_id', 'server', 'volume', 'isUpdate', 'amount'
    ];
    protected $table = 'trade_alertusers';
    public $timestamps = false;

    public function saveTradeValue($request, $server_name, $login_manager_id) {
        
        $serverid = common::getServerId($server_name);
        $explode_login = explode(',', rtrim($request->input('login'),','));
        

        try {
            foreach ($explode_login as $login) {
                $this->create(['login' => $login, 'login_manager_id' => $login_manager_id,
                    'server' => $serverid, 'volume' => $request->input('volume'), 'isUpdate' => 1, 'amount' => $request->input('amount')]);
            }
            //$last_insert_id = $this->insertGetId($user_trade_data);
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
        //return $last_insert_id;
    }

    public function updateTradeValue($request, $server_name, $login_manager_id, $login) {

        $serverid = common::getServerId($server_name);
        
        $server = $this->where('server', '=', $serverid)
                ->where('login_manager_id', '=', $login_manager_id)
                ->first();

        $user_trade = $this->where(array('login' => $login))->first();

        if (count($server) && count($user_trade)) {
            try {

                DB::table('trade_alertusers')
                        ->where('login_manager_id', $login_manager_id)
                        ->where('login', $login)
                        ->update(array(
                            "volume" => $request->input('volume'),
                            "isUpdate" => $request->input('isUpdate')
                ));
                
            } catch (\Exception $exc) {
                return FALSE;
            }
            return true;
        }
        return FALSE;
    }

    public function deleteTradeValue($server_name, $login_manager_id, $login) {

        $serverid = common::getServerId($server_name);        
        if ($login) {
            $server = $this->where('server', '=', $serverid)
                    ->where('login_manager_id', '=', $login_manager_id)
                    ->where('login', '=', $login)
                    ->get();

            if (count($server)) {
                try {
                    $this->where('server', '=', $serverid)
                            ->where('login_manager_id', '=', $login_manager_id)
                            ->where('login', '=', $login)
                            ->delete();
                } catch (\Exception $exc) {
                    return FALSE;
                }
                return true;
            }
            return FALSE;
        } else {
            //Delete All record fro loginmgr and serever
            try {
                $this->where('server', '=', $serverid)
                        ->where('login_manager_id', '=', $login_manager_id)
                        ->delete();
            } catch (\Exception $exc) {
                return FALSE;
            }
            return true;
        }
    }

    /**
     * 
     * @param type $server_name
     * @param type $login_id
     * @param type $id
     * @return type
     */
    public function getTradeValue($server_name, $login_id, $id) {
        
        $serverid = common::getServerId($server_name);
        
        $query = $this->select('*')
                ->where('server', '=', $serverid)
                ->where('login_manager_id', '=', $login_id);
        //->paginate($limit);

        if ($id) {
            $query->where('id', '=', $id);
        }
        $result = $query->get();
        
        $results = [];
        $in = 0;
        foreach($result as $usertrade){
           $results[$in]['Id'] = $usertrade['Id'];
           $results[$in]['login'] = $usertrade['login'];
           $results[$in]['login_manager_id'] = common::getloginMgr($usertrade['login_manager_id']);
           $results[$in]['server'] = common::getServerName($usertrade['server']);
           $results[$in]['volume'] = $usertrade['volume'];
           $results[$in]['isUpdate'] = $usertrade['isUpdate'];
           $results[$in]['amount'] = $usertrade['amount'];
           $in++;
        }
        

        return array('data' => $results);
    }

    /**
     * Get Login assign to server
     *  
     * @param type $servername
     * @param type $loginmgr
     * @return type array
     */
    public function getLogin($servername, $loginmgr) {
        
        $serverid = common::getServerId($servername);

        $query = DB::select("select GROUP_CONCAT(LOGIN) as login from trade_alertusers where `server` = '$serverid' AND `login_manager_id` = $loginmgr ");

        return array('login' => $query[0]->login);
    }

    /**
     * Check login is available or not
     * 
     * 
     * @param type $login
     * @param type $servername
     * @param type $login_mgr
     * @return type int
     */
    public function checkTradelogin($login, $servername, $login_mgr){
        
        $serverid = common::getServerId($servername);
        
        $checkLogin = $this->select('*')
                           ->where('server', '=', $serverid)
                           ->where('login_manager_id', '=', $login_mgr)
                           ->where('login','=',$login)->get();
        
        return count($checkLogin);
    }
}
