<?php namespace Sanatorium\Localization\Providers;

use Cartalyst\Support\ServiceProvider;

class LangtestServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Localization\Models\Langtest']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.localization.langtest.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.localization.langtest', 'Sanatorium\Localization\Repositories\Langtest\LangtestRepository');

		// Register the data handler
		$this->bindIf('sanatorium.localization.langtest.handler.data', 'Sanatorium\Localization\Handlers\Langtest\LangtestDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.localization.langtest.handler.event', 'Sanatorium\Localization\Handlers\Langtest\LangtestEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.localization.langtest.validator', 'Sanatorium\Localization\Validator\Langtest\LangtestValidator');
	}

}
