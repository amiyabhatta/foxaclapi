<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Fox\Models\Role;
use Illuminate\Http\Request as Req;

class updateRole extends Request
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
    public function rules(Req $request)
    {
        $id = $request->segment(4);        
        $role = Role::find($id);   
        
        
        return [
           'role' => 'required|unique:roles,role,'.$role->id,                          
        ];
    }
}
