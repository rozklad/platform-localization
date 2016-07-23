<?php namespace Sanatorium\Localization\Providers;

use Cartalyst\Support\ServiceProvider;
use Illuminate\Translation\Translator;
use Sanatorium\Localization\FileLoader;

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

        // Translation file loader
        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], $app['path.lang']);
        });

        // Register translator
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            // Add extensions to translator namespaces
            $extensions = $app['extensions'];
            $files = $app['files'];

            foreach ( $extensions->all() as $extension )
            {
                $lang = $extension->getPath().'/lang';

                if ($files->isDirectory($lang))
                {
                    $trans->addNamespace($extension->getSlug(), $lang);
                }
            }

            return $trans;
        });
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
