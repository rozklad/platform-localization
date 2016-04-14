<?php namespace Sanatorium\Localization\Traits;

use Illuminate\Container\Container;
use Cartalyst\Support\Traits\ContainerTrait;

trait TranslatorTrait {

	use ContainerTrait;

	/**
	 * The available locales.
	 *
	 * @var array
	 */
	protected $locales = [];

	/**
	 * {@inheritDoc}
	 */
	public function get($key, array $replace = [], $locale = null)
	{
		$fallback = $this->getFallback();

		$translation = parent::get($key, $replace, $locale);

		if ($translation === $key && $fallback !== $this->getLocale())
		{
			return parent::get($key, $replace, $fallback);
		}

		return $translation;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLocale($locale)
	{
		// Get all the available locales
		$locales = $this->getLocales();

		// Make sure we have a valid locale
		$locale = $this->isValid($locale) ? $locale : $this->getFallback();

		// Set the locale
		$this->locale = $locale;

		$this->container['config']->set('app.locale', $locale);

		setlocale(LC_ALL, array_get($locales, "{$locale}.locale", "{$locale}_".strtoupper($locale)));
	}

	/**
	 * Checks if the given locale is the current active locale.
	 *
	 * @param  string  $locale
	 * @return bool
	 */
	public function isActive($locale)
	{
		return $locale === $this->getLocale();
	}

	/**
	 * Checks if the given locale is a valid locale.
	 *
	 * @param  string  $locale
	 * @return bool
	 */
	public function isValid($locale)
	{
		return array_key_exists($locale, $this->getLocales());
	}

	/**
	 * Returns all the available locales.
	 *
	 * @return array
	 */
	public function getLocales()
	{
		return $this->locales;
	}

	/**
	 * Sets all the available locales.
	 *
	 * @param  array  $locales
	 * @return void
	 */
	public function setLocales(array $locales)
	{
		$this->locales = $locales;
	}

}