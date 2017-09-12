<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Fox\Models\lasttrade_whitelabels;
use Illuminate\Http\Request as Rq;
use Fox\Models\lasttrade_whitelabels as lw;

class updateWhiteLabel extends Request
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
    public function rules(Rq $request)
    {
        $id = $request->segment(4);            
//        $wl = lw::find($id);   
        
        return [
          'servername'  => 'required',
          'whitelabels' => 'required|unique_whitelabel:'.$id,
          'groups' => 'required',  
          'botime' => 'required|numeric',
          'fxtime' => 'required|numeric', 
        ];
    }
}
