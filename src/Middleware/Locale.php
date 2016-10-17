<?php namespace Sanatorium\Localization\Middleware;

use Closure;
use Session;

class Locale
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
        if ( $request->has('locale') ) {

            app()->setLocale($request->get('locale'));

        }

        if ( Session::has('active_language_locale') ) {

            app()->setLocale(Session::get('active_language_locale'));

        }

        return $next($request);
    }
}
