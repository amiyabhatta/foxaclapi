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
            'user_email' => 'email|unique:users,email',
            'password' => 'required',
            'conifrm_password' => 'required|same:password',
            'server_id' => 'required'
        ];
    }
}
