<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class glb_alert_setting_OM extends Model
{

    protected $fillable = [
        'volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit', 'alert_type', 'server_name'
    ];
    protected $table = 'glb_alert_setting_om';

    public function saveSetting($request, $servername, $login)
    {

        $server = $this->where('server_name', '=', $servername)
                       ->where('login','=',$login)
                       ->first();

        //Delete Details
        $alert_type = ['bo_buy', 'bo_sell', 'fx_buy', 'fx_sell', 'index_buy', 'index_sell'];
        $symbol_type_array = ['volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit'];

        $input = [];
        foreach ($alert_type as $key => $type) {
            $input[$key]['alert_type'] = $type;
            foreach ($symbol_type_array as $symbo_type) {
                $input[$key][$symbo_type] = $request->input($type . '_' . $symbo_type);
                $input[$key]['server_name'] = $servername;
                $input[$key]['login'] = $login;
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
                                "index_limit" => $input_update['index_limit']
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
    public function getSetting($servername, $login)
    {
        return $this->select('*')
                    ->where('login',$login)
                    ->where('server_name', $servername)
                    ->get();
    }

    //Delete global setting
    public function deleteSetting($servername, $login)
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
