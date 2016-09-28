<?php
/*
|--------------------------------------------------------------------------
| Localization related
|--------------------------------------------------------------------------
|
| Functions connected to localization of inputs and their values.
|
*/

if (! function_exists('transattr')) {
    /**
     * Temporary function
     * @todo replace with some conceptual translation
     * @param $slug     string Slug of attribute
     * @param $fallback string Fallback value
     * @param $locale   string Locale (null for current language)
     * @param $field    string Name of the field to translate
     * @param $option   string Option (sub field)
     * @return mixed
     */
    function transattr($slug, $fallback = null, $locale = null, $field = 'name', $option = null)
    {
        $translation = null;
        $object = app('platform.attributes')->findBySlug($slug);

        if ( is_object($object) )
        {
            $translation = \Sanatorium\Localization\Widgets\Language::get($object, $field, $locale);
        }

        if ( $translation )
        {
            if ( !is_null($option) && is_array($translation) )
            {
                if ( is_string($translation[$option]) )
                    return isset($translation[$option]) ? $translation[$option] : $fallback;
                return $fallback;
            } else {
                if ( is_string($translation) )
                    return $translation;
                return $fallback;
            }
        }

        // If field is specified, return null
        if ( $field != 'name' )
            return null;

        // @deprecated
        // @todo: Following logic should be deprecated

        $lang_slug = 'attributes.'.$slug;
        $value = trans($lang_slug);

        if ( $value == $lang_slug && $fallback != null )
            return $fallback;

        return $value;
    }
}

if (! function_exists('transvalue')) {
    /**
     * Temporary function
     * @todo replace with some conceptual translation
     * @param $slug
     * @return mixed
     */
    function transvalue($slug, $fallback = null, $locale = null)
    {
        $lang_slug = 'values.'.$slug;
        $value = trans($lang_slug);

        if ( $value == $lang_slug && $fallback != null )
            return $fallback;

        return $value;
    }
}