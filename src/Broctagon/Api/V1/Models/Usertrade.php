<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class Usertrade extends Model
{

    //trade_alertusers

    protected $fillable = [
        'login', 'login_manager_id', 'server', 'volume', 'isUpdate', 'amount'
    ];
    protected $table = 'trade_alertusers';
    public $timestamps = false;

    public function saveTradeValue($request, $server_name, $login_manager_id)
    {

        $server = $this->where('server', '=', $server_name)
                ->where('login', '=', $request->input('login'))
                ->first();


        $user_trade_data = [];
        $user_trade_data['login'] = $request->input('login');
        $user_trade_data['login_manager_id'] = $login_manager_id;
        $user_trade_data['server'] = $server_name;
        $user_trade_data['volume'] = $request->input('volume');
        $user_trade_data['isUpdate'] = 0;
        $user_trade_data['amount'] = $request->input('amount');

        if (count($server)) {
            return true;
        }

        try {
            $this->insert($user_trade_data);
        }
        catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

    public function updateTradeValue($request, $server_name, $login_manager_id, $id)
    {

        $server = $this->where('server', '=', $server_name)
                ->where('login_manager_id', '=', $login_manager_id)
                ->first();

        $user_trade = $this->find($id);

        if (count($server) && count($user_trade)) {

            //check login is already exist for same server and manager_id
            $check_login = $this->where('server', '=', $server_name)
                    ->where('login_manager_id', '=', $login_manager_id)
                    ->where('login', '=', $request->input('login'))
                    ->where('id', '!=', $id)
                    ->first();

            if (count($check_login)) {
                return 'already_asign';
            }

            try {
                $user_trade->where('server', $server_name)
                        ->where('login_manager_id', $login_manager_id)
                        ->where('id', $id)
                        ->update(array(
                            "login" => $request->input('login'),
                            "volume" => $request->input('volume'),
                            "isUpdate" => ($request->input('isUpdate') ? $request->input('isUpdate') : 0),
                            "amount" => $request->input('amount')
                ));
            }
            catch (\Exception $exc) {
                return FALSE;
            }
            return true;
        }
        return FALSE;
    }

    public function deleteTradeValue($server_name, $login_manager_id, $id)
    {

        $server = $this->where('server', '=', $server_name)
                ->where('login_manager_id', '=', $login_manager_id)
                ->where('id', '=', $id)
                ->get();

        if (count($server)) {
            try {
                $this->where('id', '=', $id)->delete();
            }
            catch (\Exception $exc) {
                return FALSE;
            }
            return true;
        }
        return FALSE;
    }

    public function getTradeValue($server_name, $login_id, $id)
    {
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

}
