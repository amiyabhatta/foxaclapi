<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\ReportGroupUser;
use DB;
use Fox\Common\Common;

class ReportGroup extends Model
{

    protected $fillable = [
        'login_mgr', 'group_name', 'server'
    ];
    protected $table = 'report_group';
    public $timestamps = false;

    /**
     * Save report group
     * 
     * 
     * @param type $servername
     * @param type $logimanagerid
     * @param type $request
     * @return boolean
     */
    public function saveReportGroup($servername, $logimanagerid, $request)
    {

        try {
            $data = DB::transaction(function() use ($servername, $logimanagerid, $request) {
                        // Add the address.
                        $InsId = $this->insertGetId([
                            'login_mgr' => $logimanagerid,
                            'group_name' => $request->input('group_name'),
                            //'server' => $servername 
                            'server' => common::getServerId($servername) 
                        ]);
                        
                        $login = rtrim($request->input('login'), ',');

                        $login = explode(',', $login);
                        foreach ($login as $logid) {
                            $reportusergroup = new ReportGroupUser;
                            $reportusergroup->login = $logid;
                            $reportusergroup->report_group_id = $InsId;
                            $reportusergroup->save();
                        }
                    });
        }
        catch (\Exception $exc) {
            return false;
        }
        return true;
    }

    /**
     * update Report group
     * 
     * 
     * @param type $servername
     * @param type $logimanagerid
     * @param type $request
     * @return boolean
     */
    public function updateReportGroup($servername, $logimanagerid, $request)
    {

        
        $group_id = $request->input('group_id');
        $check_group_id = $this->find($group_id);

        if ($check_group_id) {
            //update group name first
            try {
                $data = DB::transaction(function() use ($servername, $logimanagerid, $request, $group_id) {

                            //Update group name  
                            $serverid = common::getServerId($servername);
                            $this->where('id', $group_id)
                                    //->where('server', $servername)
                                    ->where('server', $serverid)
                                    ->where('login_mgr', $logimanagerid)
                                    ->update(['group_name' => $request->input('group_name')]);


                            //Remove login if available
                            if ($request->input('login')) {
                                $reportusergroup = new ReportGroupUser;

                                $reportusergroup->where('report_group_id', $group_id)->delete();

                                $login = rtrim($request->input('login'), ',');

                                $login = explode(',', $login);
                                foreach ($login as $logid) {
                                    $reportusergroup = new ReportGroupUser;
                                    $reportusergroup->login = $logid;
                                    $reportusergroup->report_group_id = $group_id;
                                    $reportusergroup->save();
                                }
                            }
                        });
            }
            catch (\Exception $exc) {                
                return false;
            }
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Get trade group list by some parameter
     * 
     * 
     * @param type $servername
     * @param type $logimanagerid
     * @param type $id
     * @return type array
     */
    public function getTradeGrpList($servername, $logimanagerid, $id)
    {

        if ($id) {           
            $reportgroupuser = new ReportGroupUser;
            $query = $reportgroupuser->Select('login')
                            ->where('report_group_id', '=', $id)->get()->toArray();

            $result = '';
            foreach ($query as $login) {
                $result.= $login['login'] . ',';
            }
            $result = rtrim($result, ',');
        }
        else {

            $serverid = common::getServerId($servername);
            $query = $this->select('*')
                    ->where('report_group.server', '=', $serverid)
                    ->where('report_group.login_mgr', '=', $logimanagerid);

            
            $result = array_map(function($v) {
                return [
                    'report_group_id' => $v['id'],
                    'report_group_managerid' => common::getloginMgr($v['login_mgr']),
                    'report_group_groupname' => $v['group_name'],
                    //'report_group_servername' => $v['server'],
                    'report_group_servername' => common::getServerName($v['server'])
                ];
            }, $query->get()->toArray());
        }


        return array('data' => $result);
    }

    /**
     * Delete Trade group list
     * 
     * 
     * @param type $servername
     * @param type $logimanagerid
     * @param type $request
     * @return boolean
     */
    public function deleteTradeGrpList($servername, $logimanagerid, $request)
    {

        $userId = common::getUserid($logimanagerid);
        if ($request->input('group_id')) {
            try {
                $data = DB::transaction(function() use ($servername, $logimanagerid, $request, $userId) {

                    $serverid = common::getServerId($servername);
                            //Delete data from report_group
                            $this->where('id', $request->input('group_id'))
                                    ->where('server', $serverid)
                                    ->where('login_mgr', $userId)
                                    ->delete();

                            //Delete data from report_gruoptousers
                            $reportusergroup = new ReportGroupUser;
                            
                            $reportusergroup->where('report_group_id', $request->input('group_id'))->delete();
                        });
            }
            catch (\Exception $exc) {
                return false;
            }
            return true;
        }
    }
    
    /**
     * Get group id is exist or not
     * 
     * 
     * @param type $serverName
     * @param type $logimanagerid
     * @param type $request
     * @return type integer
     */
    public function checkGroupid($serverName, $logimanagerid, $request){
        
        $serverid = common::getServerId($serverName);
        $userId = common::getUserid($logimanagerid);
        
        $check_id = $this->select('id')
                         ->where('server', $serverid)
                         ->where('login_mgr', $userId)
                         ->where('id', $request->input('group_id'))->get();
        
        return count($check_id);
    }

}
