<?php namespace Sanatorium\Localization\Handlers\Language;

use Illuminate\Events\Dispatcher;
use Sanatorium\Localization\Models\Language;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class LanguageEventHandler extends BaseEventHandler implements LanguageEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.localization.language.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.localization.language.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.localization.language.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.localization.language.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.localization.language.deleted', __CLASS__.'@deleted');
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
	public function created(Language $language)
	{
		$this->flushCache($language);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Language $language, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Language $language)
	{
		$this->flushCache($language);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Language $language)
	{
		$this->flushCache($language);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Localization\Models\Language  $language
	 * @return void
	 */
	protected function flushCache(Language $language)
	{
		$this->app['cache']->forget('sanatorium.localization.language.all');

		$this->app['cache']->forget('sanatorium.localization.language.'.$language->id);
	}

}
