<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class user_trade extends Request
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
            'volume' => 'required',
            'login'  => 'required|login_unique'
        ];
    }
}
