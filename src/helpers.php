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
     * @return mixed
     */
    function transattr($slug, $fallback = null, $locale = null)
    {
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