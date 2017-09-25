<?php

namespace Fox\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Mailsetting extends Model {

    protected $fillable = [
        'login', 'server', 'smtpserver', 'mailfrom', 'mailto', 'password', 'port', 'ssl', 'enabled'
    ];
    protected $table = 'mailsettings';
    public $timestamps = false;

    /**
     * Save mail setting info for a particular server
     * 
     * 
     * @param type $request
     * @param type $server_name
     * @param type $loginmgrid
     * @return boolean
     */
    public function saveMailSetting($request, $server_name, $loginmgrid) {

        //check data already saved or not for same server

        $checkresult = $this->where('login', '=', $loginmgrid)
                        ->where('server', '=', $server_name)->get();

        if (count($checkresult)) {

            $smtpserver = $request->input('smtpserver');
            $mailfrom = $request->input('mailfrom');
            $mailto = $request->input('mailto');
            $password = $request->input('password');
            $port = $request->input('port');
            $ssl = (int) $request->input('ssl');
            $enabled = (int) $request->input('enabled');


            //update
            try {
                DB::update("update mailsettings set smtpserver = '$smtpserver', mailfrom = '$mailfrom', mailto = '$mailto', `password` = '$password', `port` = '$port', `ssl` = $ssl,enabled = $enabled where `server` = '$server_name' AND `login` = '$loginmgrid'");
            } catch (\Exception $exc) {
                return false;
            }
        } else {
            //Insert
            $loginmgr = $loginmgrid;
            $server = $server_name;
            $smtpserver = $request->input('smtpserver');
            $mailfrom = $request->input('mailfrom');
            $mailto = $request->input('mailto');
            $password = $request->input('password');
            $port = $request->input('port');
            $ssl = (int) $request->input('ssl');
            $enabled = (int) $request->input('enabled');

            try {
                DB::insert("insert into mailsettings (login, server, smtpserver, mailfrom, mailto, `password`, `port`, `ssl`, enabled) values ($loginmgr, '$server', '$smtpserver', '$mailfrom', '$mailto', '$password', '$port', $ssl, $enabled)");
            } catch (\Exception $exc) {
                return false;
            }
        }
        return true;
    }

}
