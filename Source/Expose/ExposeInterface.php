<?php

namespace Expose;

use \Expose\ReflectionClassInterface;

interface ExposeInterface
{
	/**
	 * Expose the object
	 * @param object $object
	 * @return $this
	 */
	public static function expose($object);

	/**
	 * Return new Expose object
	 * @param \Expose\ReflectionClassInterface $reflectionClass
	 */
	public function __construct(ReflectionClassInterface $reflectionClass);

	/**
	 * Modify the value of the protected/private property in the specified object
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function attr($name, $value);

	/**
	 * Call a protected method
	 * @param string $name
	 * @param mixed* $arguments
	 * @return mixed
	 */
	public function call($name);
}
