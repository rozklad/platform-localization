<?php namespace Sanatorium\Localization\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class LocalizationsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/localization::index');
	}

}
