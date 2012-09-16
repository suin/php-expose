<?php

namespace Expose;

use \InvalidArgumentException;
use \Expose\ReflectionClassInterface;
use \Expose\ReflectionClass;

class Expose implements \Expose\ExposeInterface
{
	/** @var \Expose\ReflectionClassInterface */
	protected $reflectionClass = null;

	/**
	 * Expose the object
	 * @param object $object
	 * @return $this
	 */
	public static function expose($object)
	{
		return new static(new ReflectionClass($object));
	}

	/**
	 * Return new Expose object
	 * @param \Expose\ReflectionClassInterface $reflectionClass
	 */
	public function __construct(ReflectionClassInterface $reflectionClass)
	{
		$this->reflectionClass = $reflectionClass;
	}

	/**
	 * Modify the value of the protected/private property in the specified object
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function attr($name, $value)
	{
		$this->reflectionClass->property($name)->publicize()->value($value);
		return $this;
	}

	/**
	 * Call a protected method
	 * @param string $name
	 * @param mixed,... $arguments
	 * @return mixed
	 */
	public function call($name)
	{
		$arguments = func_get_args();
		$name = array_shift($arguments);
		return $this->reflectionClass->method($name)->publicize()->invokeArray($arguments);
	}
}
