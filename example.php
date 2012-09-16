<?php

use \Expose\Expose;

// Load test target classes
spl_autoload_register(function($c) { @include_once strtr($c, '\\_', '//').'.php'; });
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/Source');

class Sample
{
	private $foo = 0;

	/**
	 * @return int
	 */
	public function incrementFoo()
	{
		$this->foo += 1;
		return $this->foo;
	}

	/**
	 * @return string
	 */
	private function _doSomething()
	{
		return 'You may not be able to call this ordinarily';
	}
}

class SampleTest extends \PHPUnit_Framework_TestCase
{
	public function testIncrementFoo()
	{
		$sample = new Sample();

		// Modify private property
		Expose::expose($sample)->attr('foo', 100);

		$this->assertSame(101, $sample->incrementFoo());
	}

	public function test_doSomething()
	{
		$sample = new Sample();
		$this->assertSame('You may not be able to call this ordinarily', Expose::expose($sample)->call('_doSomething'));
	}
}