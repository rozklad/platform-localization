# sanatorium/localization

Localization

## Installation

### Composer

Add repository to your composer.json

    "repositories": [
      {
        "type": "composer",
        "url": "http://repo.sanatorium.ninja"
      }
    ]

Download the package

    composer require sanatorium/localization

Add following line to app/Http/Kernel.php

    protected $middleware = [
        ...
        \Sanatorium\Localization\Middleware\Locale::class
    ];

### Localize entity field

#### Register new localized entity

    // Register new localized entity
    $this->app['sanatorium.localization.localized']->localize(
        'Platform\Menus\Models\Menu'
    );

#### Localize entity field

    @localize($entity, 'field')
    
### Localize strings

General app and package language strings can be changed from

    /resources/langs/override/{LOCALE}
    /resources/langs/override/{VENDOR}/{PACKAGE}/{LOCALE}
    
These language strings are managed from

    /admin/localization/translations

## Documentation

### Languages

Manage languages available for the users on the site.

### Translations

Manage string translation in localization files.

## Changelog

- 0.4.0 - 2016-07-23 - Translation strings override, entity field localization
- 0.3.0 - 2016-05-23 - Translation strings manager
- 0.2.3 - 2016-05-22 - Basic readme file

## Support

Support not available.