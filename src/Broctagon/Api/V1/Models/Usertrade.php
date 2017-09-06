<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Usertrade extends Model {

    //trade_alertusers

    protected $fillable = [
        'login', 'login_manager_id', 'server', 'volume', 'isUpdate', 'amount'
    ];
    protected $table = 'trade_alertusers';
    public $timestamps = false;

    public function saveTradeValue($request, $server_name, $login_manager_id) {

//        $server = $this->where('server', '=', $server_name)
//                ->where('login', '=', $request->input('login'))
//                ->first();
//
//
//        $user_trade_data = [];
//        $user_trade_data['login'] = $request->input('login');
//        $user_trade_data['login_manager_id'] = $login_manager_id;
//        $user_trade_data['server'] = $server_name;
//        $user_trade_data['volume'] = $request->input('volume');
//        $user_trade_data['isUpdate'] = 1;
//        $user_trade_data['amount'] = $request->input('amount');
//
//        if (count($server)) {
//            return $server->id;
//        }

        $explode_login = explode(',', $request->input('login'));

        try {
            foreach ($explode_login as $login) {
                $this->create(['login' => $login, 'login_manager_id' => $login_manager_id,
                    'server' => $server_name, 'volume' => $request->input('volume'), 'isUpdate' => 1, 'amount' => $request->input('amount')]);
            }
            //$last_insert_id = $this->insertGetId($user_trade_data);
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
        //return $last_insert_id;
    }

    public function updateTradeValue($request, $server_name, $login_manager_id, $login) {

        $server = $this->where('server', '=', $server_name)
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

        
        if ($login) {
            $server = $this->where('server', '=', $server_name)
                    ->where('login_manager_id', '=', $login_manager_id)
                    ->where('login', '=', $login)
                    ->get();

            if (count($server)) {
                try {
                    $this->where('server', '=', $server_name)
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
                $this->where('server', '=', $server_name)
                        ->where('login_manager_id', '=', $login_manager_id)
                        ->delete();
            } catch (\Exception $exc) {
                return FALSE;
            }
            return true;
        }
    }

    public function getTradeValue($server_name, $login_id, $id) {
        $query = $this->select('*')
                ->where('server', '=', $server_name)
                ->where('login_manager_id', '=', $login_id);
        //->paginate($limit);

        if ($id) {
            $query->where('id', '=', $id);
        }
        $result = $query->get();

        return array('data' => $result);
    }

    public function getLogin($servername, $loginmgr) {

        $query = DB::select("select GROUP_CONCAT(LOGIN) as login from trade_alertusers where `server` = '$servername' AND `login_manager_id` = $loginmgr ");

        return array('login' => $query[0]->login);
    }

    public function checkTradelogin($login, $servername, $login_mgr){
        
        $checkLogin = $this->select('*')
                           ->where('server', '=', $servername)
                           ->where('login_manager_id', '=', $login_mgr)
                           ->where('login','=',$login)->get();
        
        return count($checkLogin);
    }
}
