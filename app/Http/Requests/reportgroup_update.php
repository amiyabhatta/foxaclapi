<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Request as rq;
use Fox\Models\ReportGroup;

class reportgroup_update extends Request
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
    public function rules(rq $request)
    {
        
        $id = $request->input('group_id');         
        $wl = ReportGroup::find($id); 
        
        return [
            //"group_name" => 'required|unique:report_group'.$id, 
            "group_name" => 'required|alpha', 
            "group_id" => 'required',
        ];
    }
}
