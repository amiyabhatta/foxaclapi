<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Serverlist extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'servername', 'ipaddress', 'username', 'password', 'databasename', 'masterid', 'GatewayID'
    ];
    protected $table = 'serverlist';

    /**
     * Add server list
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function addServer($request)
    {

        try {
            $this->servername = $request->input('servername');
            $this->ipaddress = $request->input('ipaddress');
            $this->username = $request->input('username');
            $this->password = $request->input('password');
            $this->databasename = $request->input('databasename');
            $this->masterid = $request->input('masterid');
            $this->GatewayID = $request->input('GatewayID');
            $this->save();
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    /**
     * update server
     * 
     * 
     * @param type $request
     * @return boolean
     */
    public function updateServer($request)
    {

        $server = $this->find($request->segment(4));

        if (!$server) {
            return false;
        }


        $server->servername = $request->input('servername');
        $server->ipaddress = $request->input('ipaddress');
        $server->username = $request->input('username');
        $server->password = $request->input('password');
        $server->databasename = $request->input('databasename');
        $server->masterid = $request->input('masterid');
        $server->GatewayID = $request->input('GatewayID');
        try {
            $server->save();
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    /**
     * Delete server by id
     * 
     * @param type $id
     * @return boolean|string
     */
    public function deleteServer($serverId)
    {

        $server = $this->find($serverId);

        if (!$server) {
            return false;
        }

        try {
            $server->where('id', '=', $serverId)->delete();
        }
        catch (\Exception $exc) {
            return 'error';
        }
        return true;
    }

    /**
     * get all server list
     * 
     * 
     * @param type $limit
     * @param type $id
     * @return type array
     */
    public function getAllServerList($limit, $serverId)
    {

        $query = $this->select('serverlist.id','serverlist.servername','serverlist.ipaddress','serverlist.username','serverlist.password','serverlist.databasename','serverlist.masterid','serverlist.GatewayID','mt4gateway.gateway_name')
                      ->leftjoin('mt4gateway', 'mt4gateway.id', '=', 'serverlist.GatewayID')
                     ->orderBy('id', 'desc'); 

        if ($serverId) {
            $query->where('serverlist.id', '=', $serverId);
        }

        $result = $query->paginate($limit);
        
        return $result;
    }

    public function getAllServerLists()
    {
        return $this->select('id', 'servername')->get();
    }

}
