<?php namespace Sanatorium\Localization\Widgets;

use App;
use Sanatorium\Localization\Models\Translations;

class Language {

	public function show($object = null, $key = null, $lang = null)
	{
		$fallback = $object->{$key};

		if ( !isset($lang) ) {
			$lang = App::getLocale();
		}

		$translation = Translations::where('locale', $lang)
							->where('entity_id', $object->id)
							->where('entity_field', $key)
							->where('entity_type', get_class($object))
							->first();

		if ( $translation ) {
			return $translation->entity_value;
		}

		return $fallback;
	}

}
