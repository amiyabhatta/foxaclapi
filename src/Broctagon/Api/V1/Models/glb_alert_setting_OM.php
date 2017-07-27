<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class glb_alert_setting_OM extends Model
{

    protected $fillable = [
        'volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit', 'alert_type', 'server_name', 'login', 'created_at'
    ];
    protected $table = 'glb_alert_setting_om';

    public function saveSetting($request, $servername, $login)
    {

        $server = $this->where('server_name', '=', $servername)
                ->where('login', '=', $login)
                ->get();


        $exist_record = [];
        foreach ($server as $server_exist_record) {
            $exist_record[$server_exist_record->alert_type]['volume_limit1'] = $server_exist_record->volume_limit1;
            $exist_record[$server_exist_record->alert_type]['volume_limit2'] = $server_exist_record->volume_limit2;
            $exist_record[$server_exist_record->alert_type]['avg_volume_limit1'] = $server_exist_record->avg_volume_limit1;
            $exist_record[$server_exist_record->alert_type]['avg_volume_limit2'] = $server_exist_record->avg_volume_limit2;
            $exist_record[$server_exist_record->alert_type]['index_limit'] = $server_exist_record->index_limit;
        }



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
                if (!count($server)) {
                    $input[$key]['created_at'] = date('Y-m-d H:i:s');
                }
            }
        }

        
        


        if (count($server)) {
//            foreach ($input as $input_update) {
//                try {
//                    $alert_type = $input_update['alert_type'];
//                    $this->where('server_name', $servername)
//                            ->where('alert_type', $input_update['alert_type'])
//                            ->where('login', $login)
//                            ->update(array(
//                                "volume_limit1" => ($input_update['volume_limit1'] ? $input_update['volume_limit1'] : $exist_record[$alert_type]['volume_limit1']),
//                                "volume_limit2" => ($input_update['volume_limit2'] ? $input_update['volume_limit2'] : $exist_record[$alert_type]['volume_limit2'] ),
//                                "avg_volume_limit1" => ($input_update['avg_volume_limit1'] ? $input_update['avg_volume_limit1'] : $exist_record[$alert_type]['avg_volume_limit1']),
//                                "avg_volume_limit2" => ($input_update['avg_volume_limit2'] ? $input_update['avg_volume_limit2'] : $exist_record[$alert_type]['avg_volume_limit2']),
//                                "index_limit" => ($input_update['index_limit'] ? $input_update['index_limit'] : $exist_record[$alert_type]['index_limit']),
//                    ));
//                }
//                catch (\Exception $exc) {
//                    //return FALSE;
//                    dd($exc);
//                }
//            }
             foreach ($server as $input_update) {
                 
                //Check alert type is available is there or not
                $check_type =  $this->where('server_name', '=', $servername)
                                    ->where('login', '=', $login)
                                    ->where('alert_type','=',$input_update->alert_type)
                                    ->get(); 
                 
                try {
                     $volume_limit1 = $request->input($input_update->alert_type.'_'.'volume_limit1');
                     $volume_limit2 = $request->input($input_update->alert_type.'_'.'volume_limit2');
                     $avg_volume_limit1 = $request->input($input_update->alert_type.'_'.'avg_volume_limit1');
                     $avg_volume_limit2 = $request->input($input_update->alert_type.'_'.'avg_volume_limit2');
                     $index_limit = $request->input($input_update->alert_type.'_'.'index_limit');
                     
                     $post_volume_limit1 = (isset($volume_limit1)) ? $volume_limit1 : $exist_record[$input_update->alert_type]['volume_limit1']; 
                     $post_volume_limit2 = (isset($volume_limit2)) ? $volume_limit2 : $exist_record[$input_update->alert_type]['volume_limit2']; 
                     $post_avg_volume_limit1 = (isset($avg_volume_limit1)) ? $avg_volume_limit1 : $exist_record[$input_update->alert_type]['avg_volume_limit1']; 
                     $post_avg_volume_limit2 = (isset($avg_volume_limit2)) ? $avg_volume_limit2 : $exist_record[$input_update->alert_type]['avg_volume_limit2']; 
                     $post_index_limit = (isset($index_limit)) ? $index_limit : $exist_record[$input_update->alert_type]['index_limit']; 
                    
                    
                    $this->where('server_name', $servername)
                            ->where('alert_type', $input_update['alert_type'])
                            ->where('login', $login)
                            ->update(array(
                                "volume_limit1" => $post_volume_limit1,
                                "volume_limit2" => $post_volume_limit2,
                                "avg_volume_limit1" => $post_avg_volume_limit1,
                                "avg_volume_limit2" => $post_avg_volume_limit2,
                                "index_limit" => $post_index_limit,
                    ));
                }
                catch (\Exception $exc) {
                    return FALSE;
                    //dd($exc);
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
            //dd("test1");
            return true;
        }
    }

    //Get global setting
    public function getSetting($servername, $login)
    {
        return $this->select('*')
                        ->where('login', $login)
                        ->where('server_name', $servername)
                        ->get();
    }

    //Delete global setting
    public function deleteSetting($servername, $login, $request)
    {
        
        try {
            if ($request->input('delete_type')) {
              $this->where('login', '=', $login)
                        ->where('server_name', '=', $servername)
                        ->where('alert_type', '=',$request->input('delete_type'))
                        ->update(array(
                                "volume_limit1" => '',
                                "volume_limit2" => '',
                                "avg_volume_limit1" => '',
                                "avg_volume_limit2" => '',
                                "index_limit" => ''));  
            }
            else {
                $this->where('login', '=', $login)
                        ->where('server_name', '=', $servername)
                        ->delete();
            }
        }
        catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

}
