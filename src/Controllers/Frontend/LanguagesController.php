<?php namespace Sanatorium\Localization\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class LanguagesController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/localization::index');
	}

	public function set($locale = null)
	{
		$request = request();

		// Previous locale
		$previous_locale = app()->getLocale();

		// Set target locale
		$request->session()->put('active_language_locale', $locale);

		// Get refering url
		$referer = $request->headers->get('referer');

		// Get relative part of uri
		$uri = str_replace( url('/'), '', $referer);
		
		// @todo - remove this exception
		// Following urls will be redirected back (their translated version does not contain '/fr/')
		$exceptional_urls = [
			'/lashes/',
			'/all-styles',
			'/natural-lashes',
			'/dramatic-lashes',
			'/profile',
			'/adresses',
			'/orders',
			'/reviews',
			'/cart',
		];

		foreach( $exceptional_urls as $exceptional_url ) {
			if ( strpos($referer, $exceptional_url) )
				return redirect()->back();
		}

		// Redirect to same uri with locale
		if ( $locale && $locale !== env('APP_LOCALE') )
			return redirect()->to('/' . $locale . $uri);							# if target locale is not the primary locale of app
		else
			return redirect()->to(str_replace($previous_locale . '/', '', $uri) );	# if target locale is primary 

		return redirect()->back();
	}
}
