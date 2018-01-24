<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Request as Req;
use Fox\Models\Serverlist;

class serverlistUpdate extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Req $request)
    {
        $id = $request->segment(4);        
        //$server = Serverlist::find($id);
        
        return [
            'servername' => 'required|unique:serverlist,servername,'.$id,
            'ipaddress' => 'required',
            'username' => 'required',
            'password' => 'required',
            'databasename' => 'required',
            'GatewayID' => 'required|exists:mt4gateway,id',
            'port' => 'required|numeric',
            'mt4api' => 'required'
        ];
    }
}
