<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class lasttrade_whitelabels extends Model
{

    protected $fillable = [
        'ServerName', 'WhiteLabels', 'Groups', 'BoTime', 'FxTime', 'Emails'
    ];
    protected $table = 'lasttrade_whitelabels';
    public $timestamps = false;

    public function getlatsTradeList($server_name, $login_id, $id)
    {

        $query = $this->select('*')
                ->where('ServerName', '=', $server_name);

        if ($id) {
            $query->where('id', '=', $id);
        }

//return $query->get();
        $result = array_map(function($v) {
            return [
                'last_trade_id' => $v['Id'],
                'last_trade_servername' => $v['ServerName'],
                'last_trade_whitelabels' => $v['WhiteLabels'],
                'last_trade_groups' => $v['Groups'],
                'last_trade_botime' => $v['BoTime'],
                'last_trade_fxtime' => $v['FxTime'],
                'last_trade_emails' => $v['Emails'],
                'last_trade_editurl' => 'api/v1/updatelasttrade/' . $v['Id']
            ];
        }, $query->get()->toArray());

        return array('data' => $result);
    }

    /*
     * Update Last trade
     */

    public function updatelatsTrade($server_name, $login_id, $id, $request)
    {

        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)
                        ->where('ServerName', $server_name)
                        ->update(['BoTime' => $request->input('botime'), 'FxTime' => $request->input('fxtime')]);
                return true;
            }
            catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /*
     * Create WhiteLabel
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
        }
        catch (\Exception $exc) {
            return false;
        }
    }

    /*
     * update WhiteLabel
     * @param array
     */

    public function updateWl($request, $id)
    {

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
            }
            catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

    /*
     * Delete whitelabel
     */

    public function deleteWl($id)
    {
        $check_id = $this->find($id);

        if (count($check_id)) {
            try {
                $this->where('Id', $id)->delete();                        
                return true;
            }
            catch (\Exception $exc) {
                return false;
            }
        }

        return false;
    }

}
