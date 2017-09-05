<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class serverlist extends Request
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
    public function rules()
    {
        return [            
            'servername' => 'required|unique:serverlist,servername',
            'ipaddress' => 'required',
            'username' => 'required',
            'password' => 'required',
            'databasename' => 'required',
            'masterid' => 'numeric',
            'GatewayID' => 'required|exists:mt4gateway,id'
        ];
    }
}
