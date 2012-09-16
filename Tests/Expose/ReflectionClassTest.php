<?php

namespace Expose;

class ReflectionClassTest extends \PHPUnit_Framework_TestCase
{
	protected $reflectionClass = '\Expose\ReflectionClass';

	public function test__construct()
	{
		$object = $this->getMock('stdClass');
		$reflectionClass = new ReflectionClass($object);
		$this->assertAttributeSame($object, 'klass', $reflectionClass);
	}

	public function testProperty()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected $foo; }', $className));
		$expect = new ReflectionProperty($className, 'foo');
		$reflectionClass = new ReflectionClass($className);
		$actual = $reflectionClass->property('foo');
		$this->assertEquals($expect, $actual);
	}

	public function testMethod()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected function foo(){ } }', $className));
		$expect = new ReflectionMethod($className, 'foo');
		$reflectionClass = new ReflectionClass($className);
		$actual = $reflectionClass->method('foo');
		$this->assertEquals($expect, $actual);
	}

	/**
	 * with no super class
	 */
	public function testGetSelfMethods()
	{
		// fixture
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected function foo(){ } }', $className));

		// expectation
		$expected = array(
			new \ReflectionMethod($className, 'foo'),
		);

		$reflectionClass = new ReflectionClass($className);
		$actual = $reflectionClass->getSelfMethods();

		$this->assertEquals($expected, $actual);
	}

	public function testGetSelfMethods_with_super_class()
	{
		// fixture
		$parentClass = 'parent'.__FUNCTION__ . md5(uniqid());
		$subClass    = 'sub'.__FUNCTION__ . md5(uniqid());

		eval("class $parentClass {
			protected function foo(){}
			protected function bar(){}
		}");

		eval("class $subClass extends $parentClass {
			protected function bar(){} // override
			protected function baz(){}
		}");

		// expectation
		$expected = array(
			new \ReflectionMethod($subClass, 'bar'),
			new \ReflectionMethod($subClass, 'baz'),
		);

		$reflectionClass = new ReflectionClass($subClass);
		$actual = $reflectionClass->getSelfMethods();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @requires PHP 5.4.0
	 */
	public function testGetSelfMethods_with_trait()
	{
		// fixture
		$trait = 'trait'.__FUNCTION__ . md5(uniqid());
		$class = __FUNCTION__ . md5(uniqid());

		eval("trait $trait {
			protected function foo(){}
			protected function bar(){}
		}");

		eval("class $class {
			use $trait;
			protected function bar(){} // override
			protected function baz(){}
		}");

		// expectation
		$expected = array(
			new \ReflectionMethod($class, 'bar'),
			new \ReflectionMethod($class, 'baz'),
		);

		$reflectionClass = new ReflectionClass($class);
		$actual = $reflectionClass->getSelfMethods();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @requires PHP 5.4.0
	 */
	public function testGetTraits()
	{
		// fixture
		$trait = 'trait'.__FUNCTION__ . md5(uniqid());
		$class = __FUNCTION__ . md5(uniqid());

		eval("trait $trait {}");
		eval("class $class {
			use $trait;
		}");

		// expectation
		$expected = array(
			$trait => new \ReflectionClass($trait),
		);

		$reflectionClass = new ReflectionClass($class);
		$actual = $reflectionClass->getTraits();

		$this->assertEquals($expected, $actual);
	}

	public function testGetTraits_just_returns_empty_array_lower_than_php5_4()
	{
		// fixture
		$class = __FUNCTION__ . md5(uniqid());

		eval("class $class {}");

		// expectation
		$expected = array();

		$reflectionClass = new ReflectionClass($class);
		$actual = $reflectionClass->getTraits();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @param $expect
	 * @param array $traits
	 * @dataProvider dataForTestUsesTraits
	 */
	public function testUsesTraits($expect, array $traits)
	{
		$reflectionClass = $this
			->getMockBuilder($this->reflectionClass)
			->disableOriginalConstructor()
			->setMethods(array('getTraits'))
			->getMock();
		$reflectionClass
			->expects($this->once())
			->method('getTraits')
			->will($this->returnValue($traits));
		$actual = $reflectionClass->usesTraits();
		$this->assertSame($expect, $actual);
	}

	public static function dataForTestUsesTraits()
	{
		return array(
			array(false, array()),
			array(true, array('trait')),
		);
	}
}
