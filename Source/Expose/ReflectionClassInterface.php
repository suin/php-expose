<?php

namespace Expose;

interface ReflectionClassInterface
{
	/**
	 * Return ReflectionProperty object
	 * @param string $name
	 * @return $this
	 */
	public function property($name);

	/**
	 * Return ReflectionMethod object
	 * @param string $name
	 * @return $this
	 */
	public function method($name);

	/**
	 * Gets a list of methods which are defined in the self class
	 * @return array
	 */
	public function getSelfMethods();

	/**
	 * Returns an array of traits used by this class
	 * @return array with trait names in keys and instances of trait's ReflectionClass in values. Returns NULL in case of an error.
	 */
	public function getTraits();

	/**
	 * Determine if this class uses traits
	 * @return bool
	 */
	public function usesTraits();
}
