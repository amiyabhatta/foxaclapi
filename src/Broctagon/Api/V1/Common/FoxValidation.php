<?php
namespace Fox\Common;

use Illuminate\Support\Facades\Validator;

trait Foxvalidation{
    
    public function deleteReportGroupValidation($request){

       return Validator::make($request->all(), [
            'group_id' => 'required|numeric'
        ]);
    }
}
