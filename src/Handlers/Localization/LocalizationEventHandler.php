<?php namespace Sanatorium\Localization\Handlers\Localization;

use Illuminate\Events\Dispatcher;
use Sanatorium\Localization\Models\Localization;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class LocalizationEventHandler extends BaseEventHandler implements LocalizationEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.localization.localization.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.localization.localization.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.localization.localization.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.localization.localization.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.localization.localization.deleted', __CLASS__.'@deleting');
		$dispatcher->listen('sanatorium.localization.localization.deleted', __CLASS__.'@deleted');
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
	public function created(Localization $localization)
	{
		$this->flushCache($localization);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Localization $localization, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Localization $localization)
	{
		$this->flushCache($localization);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleting(Localization $localization)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Localization $localization)
	{
		$this->flushCache($localization);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Localization\Models\Localization  $localization
	 * @return void
	 */
	protected function flushCache(Localization $localization)
	{
		$this->app['cache']->forget('sanatorium.localization.localization.all');

		$this->app['cache']->forget('sanatorium.localization.localization.'.$localization->id);
	}

}
