# sanatorium/localization

Localization

## Contents

1. [Installation](#installation)
2. [Documentation](#documentation)
3. [Changelog](#changelog)
4. [Support](#support)
5. [Hooks](#hooks)

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

There are two terms used throughout this package - Localization and Translation, let's describe the difference:

### Localization

We use **localization** for localized value of entity key. For example used like this:

```
{{-- Original value --}}
{{ $page->meta_title }}

{{-- Localized value --}}
@localize($page, 'meta_title')
```

### Translation

Translation refers to original translation feature of Laravel and does not need any special markup. Special admin screen to manage translations is provided.

```
trans('platform/pages::common.title')
```

### Localize entity field

#### Register new localized entity

    // Register new localized entity
    $this->app['sanatorium.localization.localized']->localize(
        'Platform\Menus\Models\Menu'
    );

#### Localize entity field

    @localize($entity, 'field')
    
### Translate strings

General app and package language strings can be changed from

    /resources/langs/override/{LOCALE}
    /resources/langs/override/{VENDOR}/{PACKAGE}/{LOCALE}
    
These language strings are managed from

    /admin/localization/translations

### Languages

Manage languages available for the users on the site.

### Single request locale setting

Set GET param 'locale' to any supported locale, like this

    http://example.com/?locale=en
    
### Persistent locale setting

#### From browser

Set GET param 'active_locale' to any supported locale, like this

    http://example.com/?active_locale=en

#### In code

Set ``active_language_locale`` session to desired locale.

### Translations

Manage string translation in localization files.

### Functions

    transattr($slug, $fallback = null, $locale = null)

    transvalue($slug, $fallback = null, $locale = null)

## Changelog

- 3.1.1 - 2016-09-23 - Helper functions
- 3.0.9 - 2016-09-15 - Platform 5, caching
- 0.4.0 - 2016-07-23 - Translation strings override, entity field localization
- 0.3.0 - 2016-05-23 - Translation strings manager
- 0.2.3 - 2016-05-22 - Basic readme file

## Support

Support not available.

## Hooks

    'shop.header' => 'sanatorium/localization::hooks.languages',  // @deprecated
    'language.switch' => 'sanatorium/localization::hooks.languages',