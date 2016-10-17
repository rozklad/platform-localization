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
        // One run language change (not persistent)
        if ( $request->has('locale') ) {

            app()->setLocale($request->get('locale'));

        }

        // Persistent language change
        if ( $request->has('active_locale') ) {

            Session::set('active_language_locale', $request->get('active_locale'));
            app()->setLocale($request->get('active_locale'));

        }

        // Persistent language change
        if ( Session::has('active_language_locale') ) {

            app()->setLocale(Session::get('active_language_locale'));

        }

        return $next($request);
    }
}
