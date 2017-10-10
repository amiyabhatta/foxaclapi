<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class GlbAlertSettingOm extends Model
{

    protected $fillable = [
        'volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit', 'alert_type', 'server_name', 'login', 'created_at'
    ];
    protected $table = 'glb_alert_setting_om';

    /**
     * Save setting for global alert settings for overall monitoring
     * 
     * 
     * @param type $request
     * @param type $servername
     * @param type $login
     * @return boolean
     */
    public function saveSetting($request, $servername, $login)
    {

        $server = $this->where('server_name', '=', $servername)
                ->where('login', '=', $login)
                ->get();
        try {
            if (count($server)) {
                //udpate record
                $existRecord = $this->getOverallData($server);
                return $this->updateRecord($server, $existRecord, $servername, $login, $request);
            }
            //Insert record
            $this->insert($this->insertData($request, $server, $servername, $login));
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

    /**
     * Get global setting
     * 
     * 
     * @param type $servername
     * @param type $login
     * @return type
     */
    public function getSetting($servername, $login)
    {
        return $this->select('*')
                        ->where('login', $login)
                        ->where('server_name', $servername)
                        ->get();
    }

    /**
     * Delete setting for overall monitoring
     * 
     * 
     * @param type $servername
     * @param type $login
     * @param type $request
     * @return boolean
     */
    public function deleteSetting($servername, $login, $request)
    {

        try {
            if ($request->input('delete_type')) {
                $this->where('login', '=', $login)
                        ->where('server_name', '=', $servername)
                        ->where('alert_type', '=', $request->input('delete_type'))
                        ->update(array(
                            "volume_limit1" => '',
                            "volume_limit2" => '',
                            "avg_volume_limit1" => '',
                            "avg_volume_limit2" => '',
                            "index_limit" => ''
                ));
                return true;
            }
            //Else delete all record
            $this->where('login', '=', $login)
                    ->where('server_name', '=', $servername)
                    ->delete();
        } catch (\Exception $exc) {
            return FALSE;
        }
        return true;
    }

    /**
     * get existing data for a server
     * 
     * @param type $server
     * @return type array
     */
    public function getOverallData($server)
    {

        $existRecord = [];
        foreach ($server as $serverExistRecord) {
            $existRecord[$serverExistRecord->alert_type]['volume_limit1'] = $serverExistRecord->volume_limit1;
            $existRecord[$serverExistRecord->alert_type]['volume_limit2'] = $serverExistRecord->volume_limit2;
            $existRecord[$serverExistRecord->alert_type]['avg_volume_limit1'] = $serverExistRecord->avg_volume_limit1;
            $existRecord[$serverExistRecord->alert_type]['avg_volume_limit2'] = $serverExistRecord->avg_volume_limit2;
            $existRecord[$serverExistRecord->alert_type]['index_limit'] = $serverExistRecord->index_limit;
        }

        return $existRecord;
    }

    /**
     * prepare data for insert
     *  
     * @param type $request
     * @param type $server
     * @param type $servername
     * @param type $login
     * @return type array
     */
    public function insertData($request, $server, $servername, $login)
    {
        //Delete Details
        $alertType = ['bo_buy', 'bo_sell', 'fx_buy', 'fx_sell', 'index_buy', 'index_sell'];
        $symbolTypeArray = ['volume_limit1', 'volume_limit2', 'avg_volume_limit1', 'avg_volume_limit2', 'index_limit'];

        $input = [];
        foreach ($alertType as $alertKey => $type) {
            $input[$alertKey]['alert_type'] = $type;
            foreach ($symbolTypeArray as $symbolType) {
                $input[$alertKey][$symbolType] = $request->input($type . '_' . $symbolType);
                $input[$alertKey]['server_name'] = $servername;
                $input[$alertKey]['login'] = $login;
                if (!count($server)) {
                    $input[$alertKey]['created_at'] = date('Y-m-d H:i:s');
                }
            }
        }
        return $input;
    }

    /**
     * Update overall monitoring data
     * 
     * @param type $server
     * @param type $existRecord
     * @param type $servername
     * @param type $login
     * @param type $request
     * @return boolean
     */
    public function updateRecord($server, $existRecord, $servername, $login, $request)
    {
        try {
            foreach ($server as $inputUpdate) {

                $volumeLimit1 = $request->input($inputUpdate->alert_type . '_' . 'volume_limit1');
                $volumeLimit2 = $request->input($inputUpdate->alert_type . '_' . 'volume_limit2');
                $avgVolumeLimit1 = $request->input($inputUpdate->alert_type . '_' . 'avg_volume_limit1');
                $avgVolumeLimit2 = $request->input($inputUpdate->alert_type . '_' . 'avg_volume_limit2');
                $indexLimit = $request->input($inputUpdate->alert_type . '_' . 'index_limit');

                $postVolumeLimit1 = (isset($volumeLimit1)) ? $volumeLimit1 : $existRecord[$inputUpdate->alert_type]['volume_limit1'];
                $postVolumeLimit2 = (isset($volumeLimit2)) ? $volumeLimit2 : $existRecord[$inputUpdate->alert_type]['volume_limit2'];
                $postAvgVolumeLimit1 = (isset($avgVolumeLimit1)) ? $avgVolumeLimit1 : $existRecord[$inputUpdate->alert_type]['avg_volume_limit1'];
                $postAvgVolumeLimit2 = (isset($avgVolumeLimit2)) ? $avgVolumeLimit2 : $existRecord[$inputUpdate->alert_type]['avg_volume_limit2'];
                $postIndexLimit = (isset($indexLimit)) ? $indexLimit : $existRecord[$inputUpdate->alert_type]['index_limit'];


                $this->where('server_name', $servername)
                        ->where('alert_type', $inputUpdate['alert_type'])
                        ->where('login', $login)
                        ->update(array(
                            "volume_limit1" => $postVolumeLimit1,
                            "volume_limit2" => $postVolumeLimit2,
                            "avg_volume_limit1" => $postAvgVolumeLimit1,
                            "avg_volume_limit2" => $postAvgVolumeLimit2,
                            "index_limit" => $postIndexLimit,
                ));
            }
        } catch (\Exception $exc) {
            return FALSE;
        }

        return true;
    }

}
