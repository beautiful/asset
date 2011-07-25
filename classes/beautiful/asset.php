<?php defined('SYSPATH') or die('No direct script access.');

abstract class Beautiful_Asset {

	protected $_type = NULL;
	
	protected $_location = NULL;
	
	protected $_attributes = array();
	
	public function __construct($location, array $attributes = NULL)
	{
		$this->_location = $location;
		
		if (isset($attributes))
		{
			$this->_attributes = $attributes;
		}
	}
	
	public function location()
	{
		return $this->_location;
	}
	
	public function type()
	{
		return $this->_type;
	}
	
	public function attributes()
	{
		return $this->_attributes;
	}
	
	abstract public function html();
	
	public function __toString()
	{
		return $this->html();
	}

}