<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class Bo_alert_setting extends Model
{
     protected $fillable = [
        'volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit', 'alert_type', 'server_name','login','symbol'
    ];
    protected $table = 'bo_alert_setting';
    
    public function saveBoAlertSetting($request, $servername , $login)
    {
        
        $server = $this->where('server_name', '=', $servername)
                       ->where('login','=',$login)
                       ->first();

        //Delete Details
        $alert_type = ['bo_buy', 'bo_sell'];
        $symbol_type_array = ['volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit'];

        $input = [];
        foreach ($alert_type as $key => $type) {
            $input[$key]['alert_type'] = $type;
            foreach ($symbol_type_array as $symbo_type) {
                $input[$key][$symbo_type] = $request->input($type . '_' . $symbo_type);
                $input[$key]['server_name'] = $servername;
                $input[$key]['login'] = $login;
                $input[$key]['symbol'] = $request->input('symbol');
                if(!count($server)){
                  $input[$key]['created_at'] = date('Y-m-d H:i:s');                  
                }
            }
        }


        if (count($server)) {
            foreach ($input as $input_update) {
                try {

                       $this->where('server_name', $servername)
                            ->where('alert_type', $input_update['alert_type'])
                            ->where('login',$login)
                            ->update(array(
                                "volume_limit1" => $input_update['volume_limit1'],
                                "volume_limit2" => $input_update['volume_limit2'],
                                "avg_volume_limit1" => $input_update['avg_volume_limit1'],
                                "avg_volume_limit2" => $input_update['avg_volume_limit2'],
                                "index_limit" => $input_update['index_limit'],  
                                "symbol" => $input_update['symbol']
                    ));
                }
                catch (\Exception $exc) {
                    return FALSE;
                }
            }
            return true;
        }
        else {
            //Insert
            try {
                $this->insert($input);
            }
            catch (\Exception $exc) {
                return FALSE;
            }

            return true;
        }
    }

    //Get global setting
    public function getBoAlertSetting($servername, $login)
    {
        return $this->select('*')
                    ->where('login',$login)
                    ->where('server_name', $servername)
                    ->get();
    }

    //Delete global setting
    public function deleteBoAlertSetting($servername, $login)
    {
        try {
            $this->where('login', '=', $login)
                 ->where('server_name', '=', $servername)
                 ->delete();
        }
        catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }
}
