<?php

namespace App\Http\Middleware;

use Closure;

class DecryptJwt
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
         try {
            $data = substr($request->header('Authorization'), 6);

            $token = 'Bearer' . decrypt($data);

            $request->headers->set('Authorization', $token);
        } catch (\Exception $exc) {

            return response()->json(['message' => 'invalid payload'], 401);
        }
        
        return $next($request);
    }
}
