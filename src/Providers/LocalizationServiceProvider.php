<?php namespace Sanatorium\Localization\Providers;

use Cartalyst\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Localization\Models\Localization']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.localization.localization.handler.event');

        // Register the manager
        $this->bindIf('sanatorium.localization.localized', 'Sanatorium\Localization\Repositories\Localization\LocalizedRepository');

        $this->registerLocalized();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.localization.localization', 'Sanatorium\Localization\Repositories\Localization\LocalizationRepository');

		// Register the data handler
		$this->bindIf('sanatorium.localization.localization.handler.data', 'Sanatorium\Localization\Handlers\Localization\LocalizationDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.localization.localization.handler.event', 'Sanatorium\Localization\Handlers\Localization\LocalizationEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.localization.localization.validator', 'Sanatorium\Localization\Validator\Localization\LocalizationValidator');
	}

	public function registerLocalized()
    {
        try
        {
            // Register new localized entity
            $this->app['sanatorium.localization.localized']->localize(
                'Platform\Menus\Models\Menu'
            );
        } catch (\ReflectionException $e)
        {
        }
    }

}
