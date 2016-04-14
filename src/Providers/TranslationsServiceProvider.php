<?php namespace Sanatorium\Localization\Providers;

use Cartalyst\Support\ServiceProvider;

class TranslationsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Localization\Models\Translations']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.localization.translations.handler.event');

		$this->prepareResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.localization.translations', 'Sanatorium\Localization\Repositories\Translations\TranslationsRepository');

		// Register the data handler
		$this->bindIf('sanatorium.localization.translations.handler.data', 'Sanatorium\Localization\Handlers\Translations\TranslationsDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.localization.translations.handler.event', 'Sanatorium\Localization\Handlers\Translations\TranslationsEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.localization.translations.validator', 'Sanatorium\Localization\Validator\Translations\TranslationsValidator');
	}

	/**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-localization');

        $this->publishes([
            $config => config_path('sanatorium-localization.php'),
        ], 'config');
    }


}
