<?php namespace Sanatorium\Localization\Providers;

use Cartalyst\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Localization\Models\Language']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.localization.language.handler.event');

		// Register all the default hooks
        $this->registerHooks();

        // Register the Blade @localize widget.
        $this->registerBladeLocalizeWidget();
		
		// @todo: Rewrite custom
		$this->registerBarryvdhLaravelTranslationManagerPackage();

		$this->prepareResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.localization.language', 'Sanatorium\Localization\Repositories\Language\LanguageRepository');

		// Register the data handler
		$this->bindIf('sanatorium.localization.language.handler.data', 'Sanatorium\Localization\Handlers\Language\LanguageDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.localization.language.handler.event', 'Sanatorium\Localization\Handlers\Language\LanguageEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.localization.language.validator', 'Sanatorium\Localization\Validator\Language\LanguageValidator');
	}

	/**
     * Register all hooks.
     *
     * @return void
     */
    protected function registerHooks()
    {
        $hooks = [
            'shop.header' => 'sanatorium/localization::hooks.languages',
        ];

        $manager = $this->app['sanatorium.hooks.manager'];

        foreach ($hooks as $position => $hook) {
            $manager->registerHook($position, $hook);
        }
    }

    /**
     * Register the Blade @localize widget.
     *
     * @return void
     */
    protected function registerBladeLocalizeWidget()
    {
        $this->app['blade.compiler']->directive('localize', function ($value) {
            return "<?php echo Widget::make('sanatorium/localization::language.show', array$value); ?>";
        });
    }

	/**
	 * Register barryvdh/laravel-translation-manager
	 * @return
	 */
	protected function registerBarryvdhLaravelTranslationManagerPackage() {
		$serviceProvider = 'Barryvdh\TranslationManager\ManagerServiceProvider';

		if ( class_exists($serviceProvider) ) {

			if (!$this->app->getProvider($serviceProvider)) {
				$this->app->register($serviceProvider);
			}

		}

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
