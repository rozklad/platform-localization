<?php namespace Sanatorium\Localization\Widgets;

use App;
use Cache;
use Sanatorium\Localization\Models\Localization;

class Language {

    public static function show($object = null, $key = null, $locale = null, $default_cache_key = 'localize')
    {
        return self::get($object, $key, $locale, $default_cache_key);
    }

	public static function get($object = null, $key = null, $locale = null, $default_cache_key = 'localize')
	{
		$fallback = $object->{$key};

		if ( !isset($locale) ) {
			$locale = App::getLocale();
		}

		$entity_id = $object->id;
		$entity_type = get_class($object);
        $entity_field = $key;

		$cache_key = implode('.', [$default_cache_key, $locale, $entity_type, $entity_id, $entity_field]);

        return Cache::rememberForever($cache_key, function() use ($fallback, $locale, $entity_id, $entity_field, $entity_type) {

            $translation = Localization::where('locale', $locale)
                ->where('entity_id', $entity_id)
                ->where('entity_field', $entity_field)
                ->where('entity_type', $entity_type)
                ->first();

            if ( $translation )
            {
                return $translation->entity_value;
            }

            return $fallback;

        });

	}

	public static function set($object, $key, $locale, $entity_value = null)
    {
        if ( !is_object($object) )
            return false;

        $entity_id = $object->id;
        $entity_type = get_class($object);
        $entity_field = $key;

        // Find localization for the given setup or create
        $localization = Localization::firstOrCreate([
            'entity_id'     => $entity_id,
            'entity_type'   => $entity_type,
            'entity_field'  => $entity_field,
            'locale'        => $locale
        ]);

        $localization->entity_value = $entity_value;

        return $localization->save();
    }

}
