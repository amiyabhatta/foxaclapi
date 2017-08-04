<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\ReportGroupUser;
use DB;

class ReportGroup extends Model
{

    protected $fillable = [
        'login_mgr', 'group_name', 'server'
    ];
    protected $table = 'report_group';
    public $timestamps = false;

    public function saveReportGroup($servername, $logimanagerid, $request)
    {

        try {
            $data = DB::transaction(function() use ($servername, $logimanagerid, $request) {
                        // Add the address.
                        $InsId = $this->insertGetId([
                            'login_mgr' => $logimanagerid,
                            'group_name' => $request->input('group_name'),
                            'server' => $servername
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

    public function updateReportGroup($servername, $logimanagerid, $request)
    {

        $group_id = $request->input('group_id');
        $check_group_id = $this->find($group_id);

        if ($check_group_id) {
            //update group name first
            try {
                $data = DB::transaction(function() use ($servername, $logimanagerid, $request, $group_id) {

                            //Update group name  

                            $this->where('id', $group_id)
                                    ->where('server', $servername)
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

            $query = $this->select('*')
                    ->where('report_group.server', '=', $servername)
                    ->where('report_group.login_mgr', '=', $logimanagerid);

            $result = array_map(function($v) {
                return [
                    'report_group_id' => $v['id'],
                    'report_group_managerid' => $v['login_mgr'],
                    'report_group_groupname' => $v['group_name'],
                    'report_group_servername' => $v['server'],
                ];
            }, $query->get()->toArray());
        }


        return array('data' => $result);
    }

    public function deleteTradeGrpList($servername, $logimanagerid, $request)
    {

        if ($request->input('group_id')) {
            try {
                $data = DB::transaction(function() use ($servername, $logimanagerid, $request) {

                            //Delete data from report_group
                            $this->where('id', $request->input('group_id'))
                                    ->where('server', $servername)
                                    ->where('login_mgr', $logimanagerid)
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

}
