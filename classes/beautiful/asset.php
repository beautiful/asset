<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Asset
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class Beautiful_Asset {

	// Type of Asset
	protected $_type;
	
	// Relative or Absolute HTTP location
	protected $_location;
	
	// Attributes for Asset
	protected $_attributes = array();
	
	/**
	 * Create new instance of Beautiful_Asset.
	 *
	 * @param   string  HTTP location of asset
	 * @param   array   list of attributes
	 * @return  void
	 */
	public function __construct($location, array $attributes = NULL)
	{
		$this->_location = $location;
		
		if (isset($attributes))
		{
			$this->_attributes = $attributes;
		}
	}
	
	/**
	 * Get type.
	 *
	 * @return  string
	 */
	public function type()
	{
		return $this->_type;
	}
	
	/**
	 * Get location.
	 *
	 * @return  string
	 */
	public function location()
	{
		return $this->_location;
	}
	
	/**
	 * Get attributes.
	 *
	 * @return  array
	 */
	public function attributes()
	{
		return $this->_attributes;
	}
	
	/**
	 * Get asset include HTML.
	 *
	 * @return    string
	 * @abstract
	 */
	abstract public function html();
	
	/**
	 * Beautiful_Asset::__toString() calls 
	 * Beautiful_Asset::html().
	 *
	 * @return  string
	 * @uses    Kohana_Exception::handler()
	 */
	public function __toString()
	{
		try
		{
			return $this->html();
		}
		catch (Exception $e)
		{
			Kohana_Exception::handler($e);
			return '';
		}
	}

}