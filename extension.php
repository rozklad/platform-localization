<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Localization',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/localization',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Localization',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.6',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Localization\Providers\TranslationsServiceProvider',
		'Sanatorium\Localization\Providers\LanguageServiceProvider',
		'Sanatorium\Localization\Providers\LangtestServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		
		Route::group([
				'prefix'    => admin_uri().'/localization/translations',
				'namespace' => 'Sanatorium\Localization\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.localization.translations.all', 'uses' => 'TranslationsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.localization.translations.all', 'uses' => 'TranslationsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.localization.translations.grid', 'uses' => 'TranslationsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.localization.translations.create', 'uses' => 'TranslationsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.localization.translations.create', 'uses' => 'TranslationsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.localization.translations.edit'  , 'uses' => 'TranslationsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.localization.translations.edit'  , 'uses' => 'TranslationsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.localization.translations.delete', 'uses' => 'TranslationsController@delete']);
			});

		Route::group([
			'prefix'    => 'localization/translations',
			'namespace' => 'Sanatorium\Localization\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.localization.translations.index', 'uses' => 'TranslationsController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/localization/languages',
				'namespace' => 'Sanatorium\Localization\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.localization.languages.all', 'uses' => 'LanguagesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.localization.languages.all', 'uses' => 'LanguagesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.localization.languages.grid', 'uses' => 'LanguagesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.localization.languages.create', 'uses' => 'LanguagesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.localization.languages.create', 'uses' => 'LanguagesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.localization.languages.edit'  , 'uses' => 'LanguagesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.localization.languages.edit'  , 'uses' => 'LanguagesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.localization.languages.delete', 'uses' => 'LanguagesController@delete']);
			});

		Route::group([
			'prefix'    => 'localization/languages',
			'namespace' => 'Sanatorium\Localization\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.localization.languages.index', 'uses' => 'LanguagesController@index']);

			Route::get('{locale}', ['as' => 'sanatorium.localization.languages.set', 'uses' => 'LanguagesController@set']);
		});

					Route::group([
				'prefix'    => admin_uri().'/localization/langtests',
				'namespace' => 'Sanatorium\Localization\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.localization.langtests.all', 'uses' => 'LangtestsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.localization.langtests.all', 'uses' => 'LangtestsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.localization.langtests.grid', 'uses' => 'LangtestsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.localization.langtests.create', 'uses' => 'LangtestsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.localization.langtests.create', 'uses' => 'LangtestsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.localization.langtests.edit'  , 'uses' => 'LangtestsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.localization.langtests.edit'  , 'uses' => 'LangtestsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.localization.langtests.delete', 'uses' => 'LangtestsController@delete']);
			});

		Route::group([
			'prefix'    => 'localization/langtests',
			'namespace' => 'Sanatorium\Localization\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.localization.langtests.index', 'uses' => 'LangtestsController@index']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('translations', function($g)
		{
			$g->name = 'Translations';

			$g->permission('translations.index', function($p)
			{
				$p->label = trans('sanatorium/localization::translations/permissions.index');

				$p->controller('Sanatorium\Localization\Controllers\Admin\TranslationsController', 'index, grid');
			});

			$g->permission('translations.create', function($p)
			{
				$p->label = trans('sanatorium/localization::translations/permissions.create');

				$p->controller('Sanatorium\Localization\Controllers\Admin\TranslationsController', 'create, store');
			});

			$g->permission('translations.edit', function($p)
			{
				$p->label = trans('sanatorium/localization::translations/permissions.edit');

				$p->controller('Sanatorium\Localization\Controllers\Admin\TranslationsController', 'edit, update');
			});

			$g->permission('translations.delete', function($p)
			{
				$p->label = trans('sanatorium/localization::translations/permissions.delete');

				$p->controller('Sanatorium\Localization\Controllers\Admin\TranslationsController', 'delete');
			});
		});

		$permissions->group('language', function($g)
		{
			$g->name = 'Languages';

			$g->permission('language.index', function($p)
			{
				$p->label = trans('sanatorium/localization::languages/permissions.index');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LanguagesController', 'index, grid');
			});

			$g->permission('language.create', function($p)
			{
				$p->label = trans('sanatorium/localization::languages/permissions.create');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LanguagesController', 'create, store');
			});

			$g->permission('language.edit', function($p)
			{
				$p->label = trans('sanatorium/localization::languages/permissions.edit');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LanguagesController', 'edit, update');
			});

			$g->permission('language.delete', function($p)
			{
				$p->label = trans('sanatorium/localization::languages/permissions.delete');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LanguagesController', 'delete');
			});
		});

		$permissions->group('langtest', function($g)
		{
			$g->name = 'Langtests';

			$g->permission('langtest.index', function($p)
			{
				$p->label = trans('sanatorium/localization::langtests/permissions.index');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LangtestsController', 'index, grid');
			});

			$g->permission('langtest.create', function($p)
			{
				$p->label = trans('sanatorium/localization::langtests/permissions.create');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LangtestsController', 'create, store');
			});

			$g->permission('langtest.edit', function($p)
			{
				$p->label = trans('sanatorium/localization::langtests/permissions.edit');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LangtestsController', 'edit, update');
			});

			$g->permission('langtest.delete', function($p)
			{
				$p->label = trans('sanatorium/localization::langtests/permissions.delete');

				$p->controller('Sanatorium\Localization\Controllers\Admin\LangtestsController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'class' => 'fa fa-circle-o',
				'name' => 'Translations',
				'uri' => 'localization/translations',
				'regex' => '/:admin\/localization\/translations/i',
				'slug' => 'admin-sanatorium-localization-translations',
				'children' => [
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Langtests',
						'uri' => 'localization/langtests',
						'regex' => '/:admin\/localization\/langtest/i',
						'slug' => 'admin-sanatorium-localization-langtest',
					],
				],
			],
			[
				'class' => 'fa fa-circle-o',
				'name' => 'Languages',
				'uri' => 'localization/languages',
				'regex' => '/:admin\/localization\/language/i',
				'slug' => 'admin-sanatorium-localization-language',
			],
		],
		'main' => [
			
		],
	],

];
