<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class createWhiteLabel extends Request
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
          'serverid'  => 'required',
          'whitelabels' => 'required|unique:lasttrade_whitelabels',
          'groups' => 'required',
          //'botime' => 'required|numeric',
          //'fxtime' => 'required|numeric'          
        ];
    }
}
