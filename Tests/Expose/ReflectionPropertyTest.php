<?php

namespace Expose;

class ReflectionPropertyTest extends \PHPUnit_Framework_TestCase
{
	public function test__construct()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected $foo; }', $className));
		$reflectionProperty = new ReflectionProperty($className, 'foo');
		$this->assertAttributeSame($className, 'klass', $reflectionProperty);
	}

	public function testPublicize()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected $foo = "foo_value"; }', $className));
		$object = new $className();

		$reflectionProperty = new ReflectionProperty($object, 'foo');
		$this->assertTrue($reflectionProperty->isProtected());
		$this->assertSame($reflectionProperty, $reflectionProperty->publicize());
		$this->assertSame('foo_value', $reflectionProperty->getValue($object));
	}

	public function testPublicize_with_private_property()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { private $foo = "foo_value"; }', $className));
		$object = new $className();

		$reflectionProperty = new ReflectionProperty($object, 'foo');
		$this->assertTrue($reflectionProperty->isPrivate());
		$this->assertSame($reflectionProperty, $reflectionProperty->publicize());
		$this->assertSame('foo_value', $reflectionProperty->getValue($object));
	}

	public function testPublicize_with_protected_static_property()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected static $foo = "foo_value"; }', $className));

		$reflectionProperty = new ReflectionProperty($className, 'foo');
		$this->assertTrue($reflectionProperty->isStatic());
		$this->assertTrue($reflectionProperty->isProtected());
		$this->assertSame($reflectionProperty, $reflectionProperty->publicize());
		$this->assertSame('foo_value', $reflectionProperty->getValue($className));
	}

	public function testPublicize_with_private_static_property()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { private static $foo = "foo_value"; }', $className));

		$reflectionProperty = new ReflectionProperty($className, 'foo');
		$this->assertTrue($reflectionProperty->isStatic());
		$this->assertTrue($reflectionProperty->isPrivate());
		$this->assertSame($reflectionProperty, $reflectionProperty->publicize());
		$this->assertSame('foo_value', $reflectionProperty->getValue($className));
	}

	public function testValue()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { public $foo = "foo_value"; }', $className));
		$object = new $className();
		$reflectionProperty = new ReflectionProperty($object, 'foo');
		$this->assertSame($reflectionProperty, $reflectionProperty->value('new_value'));
		$this->assertSame('new_value', $object->foo);
	}
}
