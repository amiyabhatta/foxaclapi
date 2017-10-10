<?php

/*
 * Permission for module
 */

namespace Fox\Services\Containers;

use Fox\Services\Contracts\MonitorContract;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Input;
use Fox\common\Base;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Fox\Common\Common;

class MonitorContainer extends Base implements MonitorContract
{

    
    public function __construct($globalSetting, $boAlert)
    {
        $this->globalSettingOm = $globalSetting;
        $this->bolAlertSetting = $boAlert;
    }
    
     /**
     * Save setting for global alert for overall monitoring
     * 
     * @param type $request
     * @return type json
     */
    public function setGlobalAlertOm($request)
    {
        //All value should be numeric
        foreach ($request->all() as $boFileds => $value) {
            if (!is_numeric($value) && $value != NULL) {
                return $this->setStatusCode(400)->respond([
                            'message' => trans($boFileds . ' ' . 'value should be numeric'),
                            'status_code' => 400
                ]);
            }
        }
        //get server name from token
        $payload = JWTAuth::parseToken()->getPayload();
        $serverName = $payload->get('server_name');
        $userInfo = JWTAuth::parseToken()->authenticate();
        $loginId = $userInfo->manager_id;
        $res = $this->globalSettingOm->saveSetting($request, $serverName, $loginId);
        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }
        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.global_alert')),
                    'status_code' => 200
        ]);
    }

    /**
     * Get settings for Overall monitoring
     * 
     * @return type json
     */
    public function getGlobalAlertOm()
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $serverName = $payload->get('server_name');
        $userInfo = JWTAuth::parseToken()->authenticate();
        $loginId = $userInfo->manager_id;
        $getGloablSettingData = $this->globalSettingOm->getSetting($serverName, $loginId);

        $ret = [];

        foreach ($getGloablSettingData as $dt) {
            
            $ret[$dt->alert_type]['volume_limit1'] = $dt->volume_limit1;
            $ret[$dt->alert_type]['volume_limit2'] = $dt->volume_limit2;
            $ret[$dt->alert_type]['avg_volume_limit1'] = $dt->avg_volume_limit1;
            $ret[$dt->alert_type]['avg_volume_limit2'] = $dt->avg_volume_limit2;
            $ret[$dt->alert_type]['index_limit'] = $dt->index_limit;
            $ret[$dt->alert_type]['server'] = $dt->server_name;
            $ret[$dt->alert_type]['login'] = $dt->login;
        }

        return response()->json($ret);
    }

    /**
     * Delete overall monitoring by server and login manager
     * 
     * @param type $request
     * @return type
     */
    public function deleteGlobalAlertOm($request)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $serverName = $payload->get('server_name');
        $userInfo = JWTAuth::parseToken()->authenticate();
        $loginId = $userInfo->manager_id;
        //Delete global setting record
        $deleteRecord = $this->globalSettingOm->deleteSetting($serverName, $loginId, $request);

        if (!$deleteRecord) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.global_alert_delete')),
                    'status_code' => 200
        ]);
    }

    /**
     * Save settings for Bo Alert
     * 
     * @param type $request
     * @return type json
     */
    public function setBoAlert($request)
    {
        //get server name from token
        $payload = JWTAuth::parseToken()->getPayload();
        $serverName = $payload->get('server_name');
        $userInfo = JWTAuth::parseToken()->authenticate();
        $loginId = $userInfo->manager_id;
        $res = $this->bolAlertSetting->saveBoAlert($request, $serverName, $loginId);

        if (!$res) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.bo_alert_setting')),
                    'status_code' => 200
        ]);
    }

    /**
     * Get settings for Bo alert
     * 
     * @return type json
     */
    public function getBoAlert()
    {

        $payload = JWTAuth::parseToken()->getPayload();
        $serverName = $payload->get('server_name');
        $userInfo = JWTAuth::parseToken()->authenticate();
        $loginId = $userInfo->manager_id;
        $getGloablSettingData = $this->bolAlertSetting->getBoAlertSetting($serverName, $loginId);

        $ret = [];

        foreach ($getGloablSettingData as $dt) {
            $ret[$dt->alert_type]['volume_limit1'] = $dt->volume_limit1;
            $ret[$dt->alert_type]['volume_limit2'] = $dt->volume_limit2;
            $ret[$dt->alert_type]['avg_volume_limit1'] = $dt->avg_volume_limit1;
            $ret[$dt->alert_type]['avg_volume_limit2'] = $dt->avg_volume_limit2;
            $ret[$dt->alert_type]['index_limit'] = $dt->index_limit;
            $ret[$dt->alert_type]['server'] = $dt->server_name;
            $ret[$dt->alert_type]['symbol'] = $dt->symbol;
            $ret[$dt->alert_type]['login'] = $dt->login;
        }

        return response()->json($ret);
    }

    /**
     * Delete bo alert 
     * 
     * @param type $request
     * @return type json
     */
    public function deleteBoAlert($request)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $serverName = $payload->get('server_name');
        $userInfo = JWTAuth::parseToken()->authenticate();
        $loginId = $userInfo->manager_id;
        $deleteBoAlertData = $this->bolAlertSetting->deleteBoAlertSetting($serverName, $loginId, $request);

        if (!$deleteBoAlertData) {
            return $this->setStatusCode(500)->respond([
                        'message' => trans('user.some_error_occur'),
                        'status_code' => 500
            ]);
        }

        return $this->setStatusCode(200)->respond([
                    'message' => (trans('user.bo_alert_setting_delete')),
                    'status_code' => 200
        ]);
    }

    
}
