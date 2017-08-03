<?php

namespace App\Http\Middleware;

use Closure;
use Fox\Common\Common;

class Superadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $check_user_role = common::checkRole();
        
        if($check_user_role != 'super_administrator'){
           return response()->json(['message' => trans('user.permission_denied'),
                    'status_code' => 403], 401); 
           
        }
        return $next($request);
    }
}
