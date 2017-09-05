<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Request as Req;
use Fox\Models\User;




class UpdateUser extends Request
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
        $user = User::find($id);          
        return [          
            'user_email' => 'email|unique:users,email,'.$user->id,
            'user_manager_id' => 'required|unique:users,manager_id,'.$user->id
        ];
    }
}
