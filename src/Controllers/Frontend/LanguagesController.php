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

		// Set target locale
		$request->session()->put('active_language_locale', $locale);

		return redirect()->back();
	}
}
