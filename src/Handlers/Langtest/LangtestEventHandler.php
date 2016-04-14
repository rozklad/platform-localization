<?php namespace Sanatorium\Localization\Handlers\Langtest;

use Illuminate\Events\Dispatcher;
use Sanatorium\Localization\Models\Langtest;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class LangtestEventHandler extends BaseEventHandler implements LangtestEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.localization.langtest.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.localization.langtest.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.localization.langtest.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.localization.langtest.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.localization.langtest.deleted', __CLASS__.'@deleted');
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
	public function created(Langtest $langtest)
	{
		$this->flushCache($langtest);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Langtest $langtest, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Langtest $langtest)
	{
		$this->flushCache($langtest);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Langtest $langtest)
	{
		$this->flushCache($langtest);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Localization\Models\Langtest  $langtest
	 * @return void
	 */
	protected function flushCache(Langtest $langtest)
	{
		$this->app['cache']->forget('sanatorium.localization.langtest.all');

		$this->app['cache']->forget('sanatorium.localization.langtest.'.$langtest->id);
	}

}
