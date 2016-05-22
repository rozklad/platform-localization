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

## Documentation

### Languages

Manage languages available for the users on the site.

### Translations

Manage string translation in localization files.

## Changelog

- 0.3.0 - 2016-23-05 - Translation strings manager
- 0.2.3 - 2016-22-05 - Basic readme file

## Support

Support not available.