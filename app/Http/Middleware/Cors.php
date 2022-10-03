<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //return $next($request);
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => '*'
        ];
        
        
        return $next($request)
        ->header('Access-Control-Allow-Origin', "*")
        ->header('Access-Control-Allow-Methods', "HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS")
        ->header('Access-Control-Allow-Headers', "X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization")
        ->header('Content-Type', 'application/json');



        if( $request->isMethod('OPTIONS') ){
            //return response()->json('OK', 200, $headers); 
            return $next($request)
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Headers', "X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization")
            ->header('HTTP/1.1 200 OK');
        }
        
    }
}
