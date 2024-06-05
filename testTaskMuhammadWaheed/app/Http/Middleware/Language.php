<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\facades\App;
use Symfony\Component\HttpFoundation\Response;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if(Session()->has("applocale") && array_key_exists(Session()->get("applocale"), config("lang")))
    //     {
    //         App::setlocale(Session()->get("applocale"));
    //     }
    //     else
    //     {
    //         App::setlocale(config("app.fallback_locale"));
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        // if (!$request->has('locale')) {
        //     $locale = config('app.locale'); // Use default locale
        //     return redirect()->route('manage-products', ['locale' => $locale]);
        // }

        return $next($request);
    }
}
