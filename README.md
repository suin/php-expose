# Expose

`Expose` makes non-public properties and methods be testable to help your unit tests with PHPUnit.

The build status of the current master branch is tracked by Travis CI: [![Build Status](https://secure.travis-ci.org/suin/php-expose.png)](http://travis-ci.org/suin/php-expose)

```php
<?php
use \Expose\Expose as e;

class Object
{
	private $_secret;
	protected $_protected;

	private function _hello($world = 'World')
	{
		return sprintf('Hello, %s', $world);
	}
}

$object = new Object();

// Expose non-public properties
e::expose($object)
	->attr('_secret', 'foo')
	->attr('_protected', 'bar');

// Call non-public method
$result = e::expose($object)->call('_hello', 'Suin');
```

## Requirements

* PHP 5.3 or later

## Installation

Just git-clone or inntall via composer.

composer.json:

```json
{
	"require": {
		"suin/php-expose": ">=1.0"
	}
}
```

Inclue `vendor/autoload.php` in your `bootstrap.php` of PHPUnit to load `Expose` components:

```
require_once 'vendor/autoload.php';
```

## License

MIT License
