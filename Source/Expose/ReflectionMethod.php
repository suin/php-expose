<?php

namespace Expose;

class ReflectionMethod extends \ReflectionMethod implements \Expose\ReflectionMethodInterface
{
	protected $klass = null;

	/**
	 * Return new ReflectionMethod object
	 * @param string|object $class
	 * @param string $name
	 */
	public function __construct($class, $name)
	{
		$this->klass = $class;
		parent::__construct($class, $name);
	}

	/**
	 * Set public accessibility
	 * @return $this
	 */
	public function publicize()
	{
		if ( $this->isPublic() === false )
		{
			$this->setAccessible(true);
		}

		return $this;
	}

	/**
	 * @param array $arguments
	 * @return mixed
	 */
	public function invokeArray(array $arguments)
	{
		array_unshift($arguments, $this->klass);
		return call_user_func_array(array($this, 'invoke'), $arguments);
	}
}
