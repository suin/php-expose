<?php

namespace Expose;

class ExposeTest extends \PHPUnit_Framework_TestCase
{
	protected $exposeClass = '\Expose\Expose';

	public function getMockBuilderForExpose()
	{
		return $this
			->getMockBuilder($this->exposeClass)
			->disableOriginalConstructor()
			->setMethods(null);
	}

	public function newExposeMock()
	{
		return $this
			->getMockBuilderForExpose()
			->getMock();
	}

	public function testExpose()
	{
		$expose = Expose::expose(new \stdClass());
		$this->assertInstanceOf($this->exposeClass, $expose);
		$this->assertAttributeInstanceOf('\Expose\ReflectionClass', 'reflectionClass', $expose);
	}

	public function test__construct()
	{
		$reflectionClass = $this->getMock('\Expose\ReflectionClassInterface');
		$expose = new Expose($reflectionClass);
		$this->assertAttributeSame($reflectionClass, 'reflectionClass', $expose);
	}

	public function testAttr()
	{
		$name  = 'attribute name';
		$value = new \stdClass();

		$reflectionClass = $this->getMock('stdClass', array(
			'property',
			'publicize',
			'value',
		));
		$reflectionClass
			->expects($this->at(0))
			->method('property')
			->with($name)
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(1))
			->method('publicize')
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(2))
			->method('value')
			->with($value);

		$expose = $this->newExposeMock();

		$property = new \ReflectionProperty($expose, 'reflectionClass');
		$property->setAccessible(true);
		$property->setValue($expose, $reflectionClass);

		$this->assertSame($expose, $expose->attr($name, $value));
	}

	public function testCall()
	{
		$name = 'method_name';
		$argument1 = new \stdClass();
		$argument2 = new \stdClass();
		$arguments = array($argument1, $argument2);
		$result = new \stdClass();

		$reflectionClass = $this->getMock('stdClass', array(
			'method',
			'publicize',
			'invokeArray',
		));
		$reflectionClass
			->expects($this->at(0))
			->method('method')
			->with($name)
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(1))
			->method('publicize')
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(2))
			->method('invokeArray')
			->with($arguments)
			->will($this->returnValue($result));

		$expose = $this->newExposeMock();

		$property = new \ReflectionProperty($expose, 'reflectionClass');
		$property->setAccessible(true);
		$property->setValue($expose, $reflectionClass);

		$this->assertSame($result, $expose->call($name, $argument1, $argument2));
	}
}
