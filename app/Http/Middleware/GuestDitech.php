<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Traits\Msg;

class GuestDitech
{
    use Msg;

    public function handle(Request $request, Closure $next)
    {
        if( !auth()->user() ) 
            return $next($request);
        else
            return Msg::warning("Go to logout and try again this");
    }
}
