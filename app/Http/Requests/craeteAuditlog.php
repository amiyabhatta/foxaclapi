<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class craeteAuditlog extends Request
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
     * @return array   2016-08-23 05:22:02
     */
    public function rules()
    {
        return [
           'logname' => 'required',
           'date' => 'required'
        ];
    }
}
