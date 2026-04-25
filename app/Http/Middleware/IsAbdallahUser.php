<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAbdallahUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $name = request()->route('name');

        if($name == "Abdallah"){
            return $next($request);
        }
        else {
            return response()->json(["error" => "Not Authenticated"],401) ;
            // return redirect('/login');
         }
        return $next($request);
    }
}
