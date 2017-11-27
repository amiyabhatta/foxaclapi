<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Fox\Models\User;
use Fox\Models\lasttrade_whitelabels;

class Userwhitelable extends Model {

    protected $fillable = [
        'userid', 'whitelabelid', 'botime', 'fxtime', 'groups'
    ];
    protected $table = 'userwhitelable';
    public $timestamps = false;

    public function assignWhitelable($userId, $request) {

        
        $wlsettingsjson = json_decode(stripslashes($request->input('whitelablesettings')), true);
       
        
//        
        foreach ($wlsettingsjson['flag'] as $key=>$value) {
                $wlSettings[] = [
                                'wlid'=>$wlsettingsjson['wlid'][$key],
                                'flag'=>$wlsettingsjson['flag'][$key],
                               'groups'=>$wlsettingsjson['groups'][$key],
                               'botime'=>$wlsettingsjson['botime'][$key],
                               'fxtime'=>$wlsettingsjson['fxtime'][$key] ];
            
        }
        
        
        //Check existing settings available for this user and selected server
        try {
            $checkExistingRecord = $this->select('*')
                            ->where('userid', '=', $userId)
                            ->get()->toArray();

            if (count($checkExistingRecord)) {
                //update record
                foreach ($checkExistingRecord as $updateExistingRecord) {
                    //delete record
                    DB::delete("delete from userwhitelable where id =" . $updateExistingRecord['Id']);
                }
            }
            foreach ($wlSettings as $wlSettingsData) {

                //Insert record
                //$serverId = $request->input('serverid');
                if ($wlSettingsData['flag'] == 1 || $wlSettingsData['flag'] == true) {
                    $wlId = $wlSettingsData['wlid'];
                    $boTime = ($wlSettingsData['botime'] ? $wlSettingsData['botime'] : 0);
                    $fxTime = ($wlSettingsData['fxtime'] ? $wlSettingsData['fxtime'] : 0);
                    $groups = $wlSettingsData['groups'];
                    DB::insert("insert into userwhitelable (userid, whitelabelid, botime, fxtime, `groups`) values ($userId, $wlId, $boTime, $fxTime, '$groups')");
                }
            }
        } catch (\Exception $exc) {
            dd($exc);
            return false;
        }

        return true;
    }

    public function getWlSettings($userId) {
        
        $res = $this->getServerUserWl($userId);
        
        $wlSettings = [];
        if (count($res)) {
            $in = 0;
            $wlSettings['managerid'] = $this->getMangerId($userId);
            $js = '';
            $wlSettings['whitelablesettings'] = [];
            foreach ($res as $results) {
                
                foreach ($results['wl_details'] as $result) {
                    
                    $wlSettings['whitelablesettings'][$in] = [
                        'wlid' => $result['wlid'],
                        'botime' => $result['botime'],
                        'fxtime' => $result['fxtime'],
                        'wl_name' => $result['wl_name'],
                        'groups' => $this->getWlGroups($result['wlid'], $userId),
                        'flag' => $this->getFlag($result['wlid'], $userId),
                    ];
                    $in++;
                }
            }
           return $wlSettings;
        }
        $wlSettings['whitelablesettings'] = '';
        return $wlSettings;
    }

    public function getWlGroups($wlid, $userId) {
        $getWlgrp = $this->select('groups')
                        ->where('userid', '=', $userId)
                        ->where('whitelabelid', '=', $wlid)->first();

        if ($getWlgrp) {
            return $getWlgrp['groups'];
        }

        $lasttradeWl = new lasttrade_whitelabels();
        $getWlgrp = $lasttradeWl->select('groups')
                        ->where('Id', $wlid)->first();

        return $getWlgrp['groups'];
    }

    public function getMangerId($userId) {
        $user = new User;
        $user = $user->getMangerId($userId);
        return $user['mangerid'];
    }

    public function getFlag($wlId, $userId) {

        $getFlag = $this->select('*')
                        ->where('userid', '=', $userId)
                        ->where('whitelabelid', '=', $wlId)->count();

        return ($getFlag ? true : false);
    }

    public function getServerUserWl($userId) {
        $user = new User;
        $res = $user->select('user_server_access.server_id','serverlist.id' ,'serverlist.servername')
                        ->join('user_server_access', 'user_server_access.user_id', '=', 'users.id')
                        ->join('serverlist', 'user_server_access.server_id', '=', 'serverlist.id')
                        ->where('users.id', $userId)->get()->toArray();

        $userDetails = [];
        $in = 0;
        foreach ($res as $getWl) {
            $userDetails[$in]['serverid'] = $getWl['server_id'];
            $userDetails[$in]['servername'] = $getWl['servername'];
            //$userDetails[$in]['wl_details'] = $this->getWlDetails($getWl['servername'], $userId);
            $userDetails[$in]['wl_details'] = $this->getWlDetails($getWl['id'], $userId);
            $in++;
        }
        return $userDetails;
    }

    public function getWlDetails($serverid, $userId) {

        $whitelable = new lasttrade_whitelabels;
        $wlDetails = $whitelable->select('Id', 'WhiteLabels')
                        //->where('ServerName', '=', $servername)->get()->toArray();
                          ->where('Serverid', '=', $serverid)->get()->toArray();

        $wlSettings = [];
        $in = 0;
        foreach ($wlDetails as $getWlSetting) {
            $wlSettings[$in]['wlid'] = $getWlSetting['Id'];
            $wlSettings[$in]['wl_name'] = $getWlSetting['WhiteLabels'];
            //get bo and fx settings for Wl
            $wlSettings[$in]['botime'] = $this->getSettings($getWlSetting['Id'], $userId, 'botime');
            $wlSettings[$in]['fxtime'] = $this->getSettings($getWlSetting['Id'], $userId, 'fxtime');
            $in++;
        }
        return $wlSettings;
    }

    public function getSettings($wlId, $userId, $settings) {

        $getSetting = $this->select($settings)
                        ->where('userid', '=', $userId)
                        ->where('whitelabelid', '=', $wlId)->first();


        return ($getSetting[$settings] ? $getSetting[$settings] : "");
    }

}
