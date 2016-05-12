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

No documentation available.

## Changelog

Changelog not available.

## Support

Support not available.