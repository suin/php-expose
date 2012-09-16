<?php

namespace Expose;

class ReflectionProperty extends \ReflectionProperty implements \Expose\ReflectionPropertyInterface
{
	protected $klass = null;

	/**
	 * Return new ReflectionProperty object
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
	 * Set value
	 * @param mixed $value
	 * @return $this
	 */
	public function value($value)
	{
		parent::setValue($this->klass, $value);
		return $this;
	}
}
