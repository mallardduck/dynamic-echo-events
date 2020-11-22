# Dynamic Echo Events
[![Travis Build Status](https://travis-ci.org/mallardduck/dynamic-echo-events.svg?branch=main)](https://travis-ci.org/mallardduck/php-whois-client)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/mallardduck/dynamic-echo-events.svg)](https://scrutinizer-ci.com/g/mallardduck/php-whois-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/mallardduck/dynamic-echo-events/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/mallardduck/php-whois-client/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/mallardduck/dynamic-echo-events/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![Latest Stable Version](https://poser.pugx.org/mallardduck/dynamic-echo-events/v/stable)](https://packagist.org/packages/mallardduck/whois-client)
[![License](https://poser.pugx.org/mallardduck/dynamic-echo-events/license)](https://packagist.org/packages/mallardduck/whois-client)
[![Coverage Status](https://coveralls.io/repos/github/mallardduck/dynamic-echo-events/badge.svg?branch=main)](https://coveralls.io/github/mallardduck/php-whois-client?branch=master)


A helper library to dynamically generate the javascript to register Echo event listeners.

Instead of manually writing JS code to register Echo event listeners, simply define the JS handler callback in the Event.
Then add the `ImplementsDynamicEcho` contact and use the `PrivateDynamicEchoChannel` trait.

## Installation

You can install the package via composer:

```bash
composer require mallardduck/dynamic-echo-events
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="MallardDuck\DynamicEcho\DynamicEchoServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    'namespace' => env('DYNAMIC_ECHO_NS', 'App\\Events')
];
```

## Usage

First, you will need to make sure that your site's `app.js` file properly configures the Echo javascript client.
This ensures the generated code has the necessary requirements.

Then, modify your sites base theme files to load the dynamic echo generated javascript. 
This should be done at a point in the template where the main `app.js` is loaded. 

Add in:

``` php
@dynamicEcho
```

Finally, when you want an event to automatically register itself in the browser use the Event contact and trait.
This is done simply by adding the `ImplementsDynamicEcho` contact and use the `PrivateDynamicEchoChannel` trait to the event.

Check the `examples` folder for working example(s) of how this is done.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [mallardduck](https://github.com/mallardduck)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
