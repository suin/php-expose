<?php

// Load test target classes
spl_autoload_register(function($c) { @include_once strtr($c, '\\_', '//').'.php'; });
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/Source');

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
