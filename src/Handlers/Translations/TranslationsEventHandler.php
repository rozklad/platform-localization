<?php namespace Sanatorium\Localization\Handlers\Translations;

use Illuminate\Events\Dispatcher;
use Sanatorium\Localization\Models\Translations;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class TranslationsEventHandler extends BaseEventHandler implements TranslationsEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.localization.translations.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.localization.translations.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.localization.translations.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.localization.translations.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.localization.translations.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Translations $translations)
	{
		$this->flushCache($translations);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Translations $translations, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Translations $translations)
	{
		$this->flushCache($translations);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Translations $translations)
	{
		$this->flushCache($translations);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Localization\Models\Translations  $translations
	 * @return void
	 */
	protected function flushCache(Translations $translations)
	{
		$this->app['cache']->forget('sanatorium.localization.translations.all');

		$this->app['cache']->forget('sanatorium.localization.translations.'.$translations->id);
	}

}
