<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Usertrade extends Model
{

    //trade_alertusers


    protected $fillable = [
        'login', 'login_manager_id', 'server', 'volume', 'isUpdate', 'amount'
    ];
    protected $table = 'trade_alertusers';
    public $timestamps = false;

    public function saveTradeValue($request, $serverName, $loginMgrId)
    {

        $explodeLogin = explode(',', $request->input('login'));

        try {
            foreach ($explodeLogin as $login) {
                $this->create(['login' => $login, 'login_manager_id' => $loginMgrId,
                    'server' => $serverName, 'volume' => $request->input('volume'), 'isUpdate' => 1, 'amount' => $request->input('amount')]);
            }
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

    public function updateTradeValue($request, $serverName, $loginMgrId, $login)
    {

        $server = $this->where('server', '=', $serverName)
                ->where('login_manager_id', '=', $loginMgrId)
                ->first();

        $userTrade = $this->where(array('login' => $login))->first();

        if (count($server) && count($userTrade)) {
            try {

                DB::table('trade_alertusers')
                        ->where('login_manager_id', $loginMgrId)
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

    public function deleteTradeValue($serverName, $loginMgrId, $login)
    {

        try {
            if ($login) {
                $server = $this->where('server', '=', $serverName)
                        ->where('login_manager_id', '=', $loginMgrId)
                        ->where('login', '=', $login)
                        ->get();

                if (count($server)) {
                    try {
                        $this->where('server', '=', $serverName)
                                ->where('login_manager_id', '=', $loginMgrId)
                                ->where('login', '=', $login)
                                ->delete();
                    } catch (\Exception $exc) {
                        return FALSE;
                    }
                    return true;
                }
                return FALSE;
            }
        //Delete All record fro loginmgr and serever
            $this->where('server', '=', $serverName)
                    ->where('login_manager_id', '=', $loginMgrId)
                    ->delete();
            return true;
        } catch (\Exception $exc) {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $server_name
     * @param type $login_id
     * @param type $id
     * @return type
     */
    public function getTradeValue($serverName, $loginMgrId, $tradeId)
    {

        $query = $this->select('*')
                ->where('server', '=', $serverName)
                ->where('login_manager_id', '=', $loginMgrId);
        //->paginate($limit);

        if ($tradeId) {
            $query->where('id', '=', $tradeId);
        }
        $result = $query->get();

        return array('data' => $result);
    }

    /**
     * Get Login assign to server
     *  
     * @param type $serverName
     * @param type $loginMgrId
     * @return type array
     */
    public function getLogin($serverName, $loginMgrId)
    {

        $query = DB::select("select GROUP_CONCAT(LOGIN) as login from trade_alertusers where `server` = '$serverName' AND `login_manager_id` = $loginMgrId ");

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
    public function checkTradelogin($login, $serverName, $loginMgrId)
    {

        $checkLogin = $this->select('*')
                        ->where('server', '=', $serverName)
                        ->where('login_manager_id', '=', $loginMgrId)
                        ->where('login', '=', $login)->get();

        return count($checkLogin);
    }

}
