<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Common\Common;
use Fox\Models\User;
use Fox\Models\Userwhitelable;
use DB;
use Fox\Models\Serverlist;

class lasttrade_whitelabels extends Model {

    protected $fillable = [
        'Serverid', 'WhiteLabels', 'Groups', 'BoTime', 'FxTime', 'Emails'
    ];
    protected $table = 'lasttrade_whitelabels';
    public $timestamps = false;

    /**
     * Gate trade list by Id
     * 
     * 
     * @param type $server_name
     * @param type $login_id
     * @param type $id
     * @return type array
     */
    public function getlatsTradeList($server_name, $mgrId, $id) {

        $wlid = '';
        if ($id) {
            $wlid = $id;
        } else {
            $serverId = common::getServerId($server_name);
            $getWLId = $this->select('id')
                            ->where('Serverid', '=', $serverId)->get()->toArray();

            foreach ($getWLId as $getWLIds) {
                $wlid .= $getWLIds['id'] . ',';
            }
            $wlid = rtrim($wlid, ',');
        }

        $result = [];
        try {
            $userId = $this->getUserId($mgrId);
            $get_detials = DB::select('select `lasttrade_whitelabels`.`Id`, `lasttrade_whitelabels`.`Serverid`, `lasttrade_whitelabels`.`WhiteLabels`,`lasttrade_whitelabels`.`Emails`, `Userwhitelable`.`groups`, `Userwhitelable`.`botime`, `Userwhitelable`.`fxtime` from `lasttrade_whitelabels` inner join `Userwhitelable` on `Userwhitelable`.`whitelabelid` = `lasttrade_whitelabels`.`Id` where `Userwhitelable`.`whitelabelid` in (' . $wlid . ') and `Userwhitelable`.`userid` =' . $userId);

            $in = 0;
            foreach ($get_detials as $wlDetails) {
                $result[$in]['id'] = $wlDetails->Id;
                $result[$in]['servername'] = common::getServerName($wlDetails->Serverid);
                $result[$in]['whitelabels'] = $wlDetails->WhiteLabels;
                $result[$in]['groups'] = $wlDetails->groups;
                $result[$in]['botime'] = $wlDetails->botime;
                $result[$in]['fxtime'] = $wlDetails->fxtime;
                $result[$in]['emails'] = $wlDetails->Emails;
                $result[$in]['editurl'] = 'api/v1/updatelasttrade/' . $wlDetails->Id;
                $in++;
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return array('data' => $result);
            // Note any method of class PDOException can be called on $ex.
        }


        return array('data' => $result);
    }

    public function getUserId($mgrId) {
        $user = new User;
        $userId = $user->select('id')
                        ->where('manager_id', '=', $mgrId)->first();

        return $userId->id;
    }

    /**
     * Update Last trade
     * 
     * @param type $server_name
     * @param type $login_id
     * @param type $id
     * @param type $request
     * @return boolean
     */
    public function updatelatsTrade($server_name, $mgrId, $id, $request) {

        $userWl = new Userwhitelable;

        $userId = $this->getUserId($mgrId);


        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $userWl->where('whitelabelid', $id)
                        ->where('userid', $userId)
                        ->update(['BoTime' => $request->input('botime'), 'FxTime' => $request->input('fxtime')]);
                return true;
            } catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /**
     * Create WhiteLabel
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function createWl($request) {

        //$this->Serverid = $request->input('servername');
        $this->Serverid = $request->input('serverid');
        $this->WhiteLabels = $request->input('whitelabels');
        $this->Groups = rtrim($request->input('groups'), ',');
        //$this->BoTime = $request->input('botime');
        //$this->FxTime = $request->input('fxtime');
        //$this->Emails = $request->input('email');

        try {
            $this->save();
            return true;
        } catch (\Exception $exc) {
            dd($exc);
            return false;
        }
    }

    /**
     * update WhiteLabel
     * 
     * 
     * @param type $request
     * @param type $id
     * @return boolean
     */
    public function updateWl($request, $id) {

        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)
                        ->update(['Serverid' => $request->input('serverid'),
                            'WhiteLabels' => $request->input('whitelabels'),
                            'Groups' => $request->input('groups'),
                                //'BoTime' => $request->input('botime'),
                                //'FxTime' => $request->input('fxtime'),
                                //'Emails' => $request->input('email'),
                ]);
                return true;
            } catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /**
     * Delete white label
     * 
     * @param type $id
     * @return boolean
     */
    public function deleteWl($id) {
        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)->delete();

                //Delere record from userwhitelable
                $userWl = new Userwhitelable;
                $checkWlId = $userWl->where('whitelabelid', $id)->count();
                if ($checkWlId) {
                    $userWl->where('whitelabelid', $id)->delete();
                }
                return true;
            } catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /**
     * Get white labels 
     * 
     * 
     * @param type $server_name
     * @param type $id
     * @return boolean
     */
    public function getWhiteLabelList($server_name, $id) {

        $check_user_role = common::checkRole();

        if ($check_user_role == 'super_administrator') {
            $query = $this->select('*')
                    ->orderBy('id', 'desc');

            if ($id) {
                $query->where('id', '=', $id);
            }



            try {
                $result = array_map(function($v) {
                    return [
                        'id' => (int) $v['Id'],
                        'server' => $this->getServername($v['Serverid']),
                        'serverid' => (int) $v['Serverid'],
                        'whitelabels' => $v['WhiteLabels'],
                        'groups' => $v['Groups'],
                        'botime' => $v['BoTime'],
                        'fxtime' => $v['FxTime'],
                        'emails' => $v['Emails']
                    ];
                }, $query->get()->toArray());
            } catch (\Exception $exc) {
                return false;
            }

            return array('data' => $result);
        }
        //if not superadmin
        $query = $this->select('*')
                ->where('ServerName', $server_name);

        if ($id) {
            $query->where('id', '=', $id);
        }



        try {
            $result = array_map(function($v) {
                return [
                    'id' => $v['Id'],
                    'server' => $v['ServerName'],
                    'whitelabels' => $v['WhiteLabels'],
                ];
            }, $query->get()->toArray());
        } catch (\Exception $exc) {
            return false;
        }

        return array('data' => $result);
    }

    public function whitelableList($server) {
        $wlList = $this->select('id', 'WhiteLabels')
                        ->where('ServerName', '=', $server)
                        ->get()->toArray();

        return array('data' => $wlList);
    }

    public function getServerList($userId) {

        $getserver = new user_server_access();

        $result = $getserver->select('serverlist.servername', 'serverlist.id')
                ->leftjoin('users', 'user_server_access.user_id', '=', 'users.id')
                ->leftjoin('serverlist', 'serverlist.id', '=', 'user_server_access.server_id')
                ->where('user_server_access.user_id', '=', $userId)
                ->get();

        $server_array = [];
        $i = 0;
        foreach ($result as $serverdetails) {
            $server_array[$i]['id'] = $serverdetails['id'];
            $server_array[$i]['server_name'] = $serverdetails['servername'];
            $i++;
        }
        return $server_array;
    }

    public function getServername($serverId) {
        $getServername = Serverlist::select('servername')->where('id', '=', $serverId)->first();
        return $getServername['servername'];
    }

    public function getServerId($serverName) {

        $getServerid = Serverlist::select('id')->where('servername', '=', $serverName)->first();
        return $getServerid['id'];
    }

}
