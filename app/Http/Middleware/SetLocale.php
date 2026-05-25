<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = 'en'; 

        if ($request->hasHeader('Accept-Language')) {
            $header = $request->header('Accept-Language');
            if (in_array($header, ['en', 'it'])) {
                $locale = $header;
            }
        }

        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}