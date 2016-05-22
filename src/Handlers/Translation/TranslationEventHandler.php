<?php namespace Sanatorium\Localization\Handlers\Translation;

use Illuminate\Events\Dispatcher;
use Sanatorium\Localization\Models\Translation;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class TranslationEventHandler extends BaseEventHandler implements TranslationEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.localization.translation.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.localization.translation.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.localization.translation.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.localization.translation.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.localization.translation.deleted', __CLASS__.'@deleting');
		$dispatcher->listen('sanatorium.localization.translation.deleted', __CLASS__.'@deleted');
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
	public function created(Translation $translation)
	{
		$this->flushCache($translation);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Translation $translation, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Translation $translation)
	{
		$this->flushCache($translation);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleting(Translation $translation)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Translation $translation)
	{
		$this->flushCache($translation);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Localization\Models\Translation  $translation
	 * @return void
	 */
	protected function flushCache(Translation $translation)
	{
		$this->app['cache']->forget('sanatorium.localization.translation.all');

		$this->app['cache']->forget('sanatorium.localization.translation.'.$translation->id);
	}

}
