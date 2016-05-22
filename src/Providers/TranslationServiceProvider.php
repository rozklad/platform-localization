<?php namespace Sanatorium\Localization\Providers;

use Cartalyst\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Localization\Models\Translation']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.localization.translation.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.localization.translation', 'Sanatorium\Localization\Repositories\Translation\TranslationRepository');

		// Register the data handler
		$this->bindIf('sanatorium.localization.translation.handler.data', 'Sanatorium\Localization\Handlers\Translation\TranslationDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.localization.translation.handler.event', 'Sanatorium\Localization\Handlers\Translation\TranslationEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.localization.translation.validator', 'Sanatorium\Localization\Validator\Translation\TranslationValidator');
	}

}
