<?php

namespace Expose;

use \Expose\ReflectionProperty;
use \Expose\ReflectionMethod;

class ReflectionClass extends \ReflectionClass implements \Expose\ReflectionClassInterface
{
	protected $klass = null;

	/**
	 * Return new ReflectionClass object
	 * @param string|object $argument
	 */
	public function __construct($argument)
	{
		parent::__construct($argument);
		$this->klass = $argument;
	}

	/**
	 * Return ReflectionProperty object
	 * @param string $name
	 * @return \Expose\ReflectionPropertyInterface
	 */
	public function property($name)
	{
		return new ReflectionProperty($this->klass, $name);
	}

	/**
	 * Return ReflectionMethod object
	 * @param string $name
	 * @return \Expose\ReflectionMethodInterface
	 */
	public function method($name)
	{
		return new ReflectionMethod($this->klass, $name);
	}

	/**
	 * Gets a list of methods which are defined in the self class
	 * @return \ReflectionMethod[]
	 */
	public function getSelfMethods()
	{
		$myName     = $this->getName();
		$myFilename = $this->getFileName();
		$hasTrait   = $this->usesTraits();
		$methods    = $this->getMethods();

		foreach ( $methods as $key => $method )
		{
			/** @var $method \ReflectionMethod */
			$thatName     = $method->getDeclaringClass()->getName();
			$thatFilename = $method->getFileName();

			if ( $myName != $thatName )
			{
				unset($methods[$key]);
			}
			else
			{
				if ( $hasTrait === true and $myFilename != $thatFilename )
				{
					unset($methods[$key]);
				}
			}
		}

		return $methods;
	}

	/**
	 * Returns an array of traits used by this class
	 * @return array with trait names in keys and instances of trait's ReflectionClass in values. Returns NULL in case of an error.
	 */
	public function getTraits()
	{
		$traits = array();

		if ( method_exists('\ReflectionClass', 'getTraits') )
		{
			$traits = parent::getTraits();
		}

		return $traits;
	}

	/**
	 * Determine if this class uses traits
	 * @return bool
	 */
	public function usesTraits()
	{
		if ( count($this->getTraits()) > 0 )
		{
			return true;
		}

		return false;
	}
}
