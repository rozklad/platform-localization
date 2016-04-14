<?php namespace Sanatorium\Localization\Widgets;

use Sanatorium\Localization\Models\Language;

class Hooks {

	public function languages($class = null)
	{
		$languages = Language::all();

		$active_language = Language::where( 'locale', Language::getActiveLanguageLocale() )->first();

		if ( !$languages )
			return null;

		if ( !$active_language )
			return null;

		return view('sanatorium/localization::hooks/languages', compact('languages', 'active_language', 'class'));
	}

}
