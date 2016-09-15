<?php namespace Sanatorium\Localization\Widgets;

use Sanatorium\Localization\Models\Language as LanguageModel;

class Hooks {

	public function languages($class = null)
	{
		$languages =  app('sanatorium.localization.language')->findAll();

		$active_language =  LanguageModel::where( 'locale',  LanguageModel::getActiveLanguageLocale() )->first();

		if ( !$languages )
			return null;

		if ( !$active_language )
			return null;

		return view('sanatorium/localization::hooks/languages', compact('languages', 'active_language', 'class'));
	}

}
