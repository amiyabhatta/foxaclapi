<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use Fox\Models\Permissions;
use DB;
use Fox\Common\Common;

class TabSelected extends Model {

    protected $fillable = [
        'login_mgr', 'server', 'permission_id', 'status'
    ];
    protected $table = 'tab_selected';
    public $timestamps = false;

    /**
     * Save tab setting
     * 
     * 
     * @param type $request
     * @param type $server
     * @param type $loginmgr
     * @return boolean
     */
    public function saveTab($request, $server, $loginmgr) {

        if ($request->input('tab_setting')) {
            $permission = explode(',', $request->input('tab_setting'));

            return $this->permissionId($permission, $server, $loginmgr);
        }

        try {
            DB::update("update tab_selected set status =0 where server = '$server' AND login_mgr = $loginmgr");
        } catch (\Exception $exc) {
            return false;
            //dd($exc);
        }
        return true;
    }

    /**
     * Update permission id
     * 
     * @param type $perm_name
     * @param type $server
     * @param type $loginmgr
     * @return boolean
     */
    public function permissionId($perm_name, $server, $loginmgr) {


        $perm = new Permissions;
        $permId = $perm->select('id')
                        ->whereIn('name', $perm_name)->get()->toArray();

        $serverid = common::getServerId($server);
        //check tab is selected or not
        try {
            $perm_id = '';
            foreach ($permId as $permIds) {

                $tabSelect = $this->select('*')
                                ->where('permission_id', $permIds['id'])
                                ->where('server', $serverid)
                                ->where('login_mgr', $loginmgr)->get()->toArray();


                if (!empty($tabSelect) && ($tabSelect[0]['permission_id']) && ($tabSelect[0]['status'] == 0)) { //update
                    //dd('hi dibya');
                    $data = $this->where('id', $tabSelect[0]['id'])
                            ->update(['status' => 1]);
                } else if (empty($tabSelect)) { //Insert
                    $this->create(['server' => $serverid, 'login_mgr' => $loginmgr,
                        'permission_id' => $permIds['id'], 'status' => 1]);
                }
                $perm_id .= $permIds['id'] . ',';
            }
            //Change status of permission if not selected
            $ids = rtrim($perm_id, ',');
            $permId = DB::update("update tab_selected set status =0 where server = '$serverid' AND login_mgr = $loginmgr AND permission_id NOT IN ($ids)");
            return true;
        } catch (\Exception $exc) {
            return false;
            //dd($exc);
        }
    }

    /**
     * Get selected tab
     * 
     * 
     * @param type $server
     * @param type $loginmgr
     * @return type array
     */
    public function getTab($server, $loginmgr) {
        
        $serverid = common::getServerId($server);
        $perm = new Permissions;
        $getTabSetting = $perm->select('permissions.name', 'tab_selected.status')
                        ->leftjoin('tab_selected', 'permissions.id', '=', 'tab_selected.permission_id')
                        ->where('tab_selected.server', '=', $serverid)
                        ->where('tab_selected.login_mgr', '=', $loginmgr)->get()->toArray();
        $tabsetting = [];
        $i = 0;
        foreach ($getTabSetting as $getTabSettings) {
            $tabsetting[$i]['tabname'] = $getTabSettings['name'];
            $tabsetting[$i]['status'] = $getTabSettings['status'];
            $i++;
        }

        return $tabsetting;
    }

}
