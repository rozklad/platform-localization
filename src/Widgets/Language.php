<?php namespace Sanatorium\Localization\Widgets;

use App;
use Cache;
use Sanatorium\Localization\Models\Localization;

class Language {

    public static function show($object = null, $key = null, $locale = null, $default_cache_key = null, $use_fallback = true, $cache = true)
    {
        return app('sanatorium.localization.localization')->get($object, $key, $locale, $default_cache_key, $use_fallback, $cache);
    }

    /**
     * @deprecated
     */
	public static function get($object = null, $key = null, $locale = null, $default_cache_key = null, $use_fallback = false, $cache = true)
	{
        return app('sanatorium.localization.localization')->get($object, $key, $locale, $default_cache_key, $use_fallback, $cache);
	}

    /**
     * @deprecated
     */
	public static function set($object, $key, $locale, $entity_value = null, $default_cache_key = null)
    {
        return app('sanatorium.localization.localization')->set($object, $key, $locale, $entity_value, $default_cache_key);
    }

}
