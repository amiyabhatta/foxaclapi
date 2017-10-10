<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Common\Common;

class LastTradeWhitelabels extends Model
{

    protected $fillable = [
        'ServerName', 'WhiteLabels', 'Groups', 'BoTime', 'FxTime', 'Emails'
    ];
    protected $table = 'lasttrade_whitelabels';
    public $timestamps = false;

    /**
     * Gate trade list by Id
     * 
     * 
     * @param type $serverName
     * @param type $login_id
     * @param type $id
     * @return type array
     */
    public function getlatsTradeList($serverName, $lastTradeId)
    {
        
        $query = $this->select('*')
                ->where('ServerName', '=', $serverName);

        if ($lastTradeId) {
            $query->where('id', '=', $lastTradeId);
        }

        $result = array_map(function($tradeResult) {
            return [
                'id' => $tradeResult['Id'],
                'servername' => $tradeResult['ServerName'],
                'whitelabels' => $tradeResult['WhiteLabels'],
                'groups' => $tradeResult['Groups'],
                'botime' => $tradeResult['BoTime'],
                'fxtime' => $tradeResult['FxTime'],
                'emails' => $tradeResult['Emails'],
                'editurl' => 'api/v1/updatelasttrade/' . $tradeResult['Id']
            ];
        }, $query->get()->toArray());

        return array('data' => $result);
    }

    /**
     * Update Last trade
     * 
     * @param type $serverName
     * @param type $login_id
     * @param type $id
     * @param type $request
     * @return boolean
     */
    public function updatelatsTrade($serverName, $lastTradeId, $request)
    {

        $checkId = $this->find($lastTradeId);

        if (count($checkId)) {
            try {
                $this->where('Id', $lastTradeId)
                        ->where('ServerName', $serverName)
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
    public function createWl($request)
    {

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

    /**
     * update WhiteLabel
     * 
     * 
     * @param type $request
     * @param type $id
     * @return boolean
     */
    public function updateWl($request, $wlId)
    {

        $checkId = $this->find($wlId);

        if (count($checkId)) {
            try {
                $this->where('Id', $wlId)
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

    /**
     * Delete white label
     * 
     * @param type $id
     * @return boolean
     */
    public function deleteWl($wlId)
    {
        $checkId = $this->find($wlId);

        if (count($checkId)) {
            try {
                $this->where('Id', $wlId)->delete();
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
     * @param type $serverName
     * @param type $id
     * @return array
     */
    public function getWhiteLabelList($serverName, $wlId)
    {
        $checkUserRole = common::checkRole();

        if ($checkUserRole == 'super_administrator') {
            $query = $this->select('*')
                    ->orderBy('id', 'desc');

            if ($wlId) {
                $query->where('id', '=', $wlId);
            }



            try {
                $result = array_map(function($tardeList) {
                    return [
                        'id' => $tardeList['Id'],
                        'server' => $tardeList['ServerName'],
                        'whitelabels' => $tardeList['WhiteLabels'],
                        'groups' => $tardeList['Groups'],
                        'botime' => $tardeList['BoTime'],
                        'fxtime' => $tardeList['FxTime'],
                        'emails' => $tardeList['Emails']
                    ];
                }, $query->get()->toArray());
            } catch (\Exception $exc) {
                return false;
            }

            return array('data' => $result);
        }
        //if not superadmin
        $query = $this->select('*')
                ->where('ServerName', $serverName);

        if ($wlId) {
            $query->where('id', '=', $wlId);
        }



        try {
            $result = array_map(function($tardeList) {
                return [
                    'id' => $tardeList['Id'],
                    'server' => $tardeList['ServerName'],
                    'whitelabels' => $tardeList['WhiteLabels'],
                ];
            }, $query->get()->toArray());
        } catch (\Exception $exc) {
            return false;
        }

        return array('data' => $result);
    }

}
