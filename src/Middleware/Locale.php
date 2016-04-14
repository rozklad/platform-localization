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
        if ( Session::has('active_language_locale') ) {

            app()->setLocale(Session::get('active_language_locale'));

        } else {

            $country = \Sanatorium\Addresses\Widgets\Hooks::suggestCountry();

            if ( $country == 'France' ) {

                app()->setLocale('fr');
                request()->session()->put('active_currency_id', 3);

            }

            if ( $country == 'United Kingdom' ) {

                request()->session()->put('active_currency_id', 4);

            }


        }

        return $next($request);
    }
}
