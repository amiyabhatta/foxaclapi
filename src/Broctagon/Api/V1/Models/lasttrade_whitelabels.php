<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Common\Common;

class lasttrade_whitelabels extends Model {

    protected $fillable = [
        'ServerName', 'WhiteLabels', 'Groups', 'BoTime', 'FxTime', 'Emails'
    ];
    protected $table = 'lasttrade_whitelabels';
    public $timestamps = false;

    public function getlatsTradeList($server_name, $login_id, $id) {

        $query = $this->select('*')
                ->where('ServerName', '=', $server_name);

        if ($id) {
            $query->where('id', '=', $id);
        }

//return $query->get();
        $result = array_map(function($v) {
            return [
                'id' => $v['Id'],
                'servername' => $v['ServerName'],
                'whitelabels' => $v['WhiteLabels'],
                'groups' => $v['Groups'],
                'botime' => $v['BoTime'],
                'fxtime' => $v['FxTime'],
                'emails' => $v['Emails'],
                'editurl' => 'api/v1/updatelasttrade/' . $v['Id']
            ];
        }, $query->get()->toArray());

        return array('data' => $result);
    }

    /*
     * Update Last trade
     */

    public function updatelatsTrade($server_name, $login_id, $id, $request) {

        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)
                        ->where('ServerName', $server_name)
                        ->update(['BoTime' => $request->input('botime'), 'FxTime' => $request->input('fxtime')]);
                return true;
            } catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /*
     * Create WhiteLabel
     */

    public function createWl($request) {

        $this->ServerName = $request->input('servername');
        $this->WhiteLabels = $request->input('whitelabels');
        $this->Groups = rtrim($request->input('groups'), ',');
        $this->BoTime = $request->input('botime');
        $this->FxTime = $request->input('fxtime');
        $this->Emails = $request->input('email');

        try {
            $this->save();
            return true;
        } catch (\Exception $exc) {
            return false;
        }
    }

    /*
     * update WhiteLabel
     * @param array
     */

    public function updateWl($request, $id) {

        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)
                        ->update(['ServerName' => $request->input('servername'),
                            'WhiteLabels' => $request->input('whitelabels'),
                            'Groups' => $request->input('groups'),
                            'BoTime' => $request->input('botime'),
                            'FxTime' => $request->input('fxtime'),
                            'Emails' => $request->input('email'),
                ]);
                return true;
            } catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /*
     * Delete whitelabel
     */

    public function deleteWl($id) {
        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)->delete();
                return true;
            } catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /*
     * Get whitelabels 
     */

    public function getWhiteLabelList($server_name, $id) {
        $check_user_role = common::checkRole();
        
        if ($check_user_role == 'super_administrator') {
            $query = $this->select('*');

            if ($id) {
                $query->where('id', '=', $id);
            }



            try {
                $result = array_map(function($v) {
                    return [
                        'id' => $v['Id'],
                        'server' => $v['ServerName'],
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

}
