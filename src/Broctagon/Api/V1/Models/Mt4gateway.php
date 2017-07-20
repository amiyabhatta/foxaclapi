<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;

class Mt4gateway extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'host', 'port', 'master_password', 'username', 'gateway_name'
    ];
    protected $table = 'mt4gateway';

    public function addGateway($request)
    {

        try {
            $this->gateway_name = $request->input('gatewayname');
            $this->host = $request->input('host');
            $this->port = $request->input('port');
            $this->master_password = $request->input('password');
            $this->username = $request->input('username');
            $this->save();
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    public function updateGateway($request)
    {

        $gateway = $this->find($request->segment(4));

        if (!$gateway) {
            return false;
        }


        $gateway->gateway_name = $request->input('gatewayname');
        $gateway->host = $request->input('host');
        $gateway->port = $request->input('port');
        $gateway->master_password = $request->input('password');
        $gateway->username = $request->input('username');

        try {
            $gateway->save();
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    public function deleteGateway($id)
    {

        $gw = $this->find($id);

        if (!$gw) {
            return false;
        }

        try {
            $gw->where('id', '=', $id)->delete();
        }
        catch (\Exception $exc) {
            return 'error';
        }
        return true;
    }

    public function getAllGwList($limit, $id)
    {
        $query = $this->select('*');

        if ($id) {
            $query->where('id', "=", $id);
        }

        $result = $query->paginate($limit);

        return $result;
    }

}
