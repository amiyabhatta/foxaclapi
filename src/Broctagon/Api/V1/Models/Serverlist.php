<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;


class Serverlist extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'servername','ipaddress','username','password','databasename','masterid','GatewayID'
    ];
    
    protected $table = 'serverlist';
    
    public function addServer($request){
    
        try {
            $this->servername = $request->input('servername');
            $this->ipaddress = $request->input('ipaddress');
            $this->username = $request->input('username');
            $this->password = $request->input('password');
            $this->databasename = $request->input('databasename');
            $this->GatewayID = $request->input('GatewayID');
            $this->save();
        }
        catch (\Exception $exc) {            
            return false;
        }
        return true;
    }
    
    public function updateServer($request){
        
        $server = $this->find($request->segment(4));
        
        if (!$server) {
            return false;
        }
        
      
        $server->servername = $request->input('servername');
        $server->ipaddress = $request->input('ipaddress');
        $server->username = $request->input('username');
        $server->password = $request->input('password');
        $server->databasename = $request->input('databasename');
        $server->GatewayID = $request->input('GatewayID');
        try{
            $server->save();
        }
        catch(\Exception $exc){                
           return false; 
        }
        return true;
    }
    
    public function deleteServer($id){
        
        $server = $this->find($id);

        if (!$server) {
            return false;
        }

        try {
            $server->where('id', '=', $id)->delete();
        }
        catch (\Exception $exc) {
            return 'error';
        }
        return true;
    }
    
    public function getAllServerList($limit){        
       return $this->join('mt4gateway','mt4gateway.id','=','serverlist.GatewayID')                   
                   ->paginate($limit);  
    }
}
