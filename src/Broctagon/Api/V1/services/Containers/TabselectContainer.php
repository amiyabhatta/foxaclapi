<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\TabselectContract;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Fox\Common\Common;
use Validator;


class TabselectContainer extends Base implements TabselectContract
{
    
    public function __construct($tabSelect)
    {
       $this->tabselectmodel = $tabSelect;
    }
    
    /**
     * Save tab settings
     * 
     * @param type $request
     * @return type json
     */
    public function saveTab($request)
    {
        
        $validate = Validator::make($request->all(), [
                    "tab_setting" => 'required|check_validtab',
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }

        $servermgrId = common::serverManagerId();
        
        $res = $this->tabselectmodel->saveTab($request, $servermgrId['server_name'], $servermgrId['login']);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.save_tab')),
                    'status_code' => 200
        ]);
    }

    /**
     * get saved tab setting details
     * 
     * @return type json
     */
    public function getTabSetting()
    {
        $servermgrId = common::serverManagerId();
        return $this->tabselectmodel->getTab($servermgrId['server_name'], $servermgrId['login']);
    }
    
    
}
