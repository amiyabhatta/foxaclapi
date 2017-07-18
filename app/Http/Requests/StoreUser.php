<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreUser extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name' => 'required',
            'user_manager_id' =>  'required|numeric|unique:users,manager_id',
            //'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required',   
            'server_id' => 'required'
        ];
    }
}
