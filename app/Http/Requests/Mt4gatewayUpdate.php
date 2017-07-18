<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class Mt4gatewayUpdate extends Request
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
            'gatewayname' => 'required',
            'host' =>  'required',
            'port' => 'required',
            'password' => 'required',
            'username' => 'required',
        ];
    }
}
