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
	
	// Settings for Asset
	protected $_settings = array();
	
	/**
	 * Create new instance of Beautiful_Asset.
	 *
	 * @param   string  HTTP location of asset
	 * @param   array   list of attributes
	 * @return  void
	 */
	public function __construct($location, array $settings = array())
	{
		$this->_location = $location;

		$this->_settings = $settings;
	}

	/**
	 * Get a single or use a default value.
	 *
	 * @param   string  key
	 * @param   mixed   default value
	 * @return  mixed
	 */
	protected function setting($key, $default = NULL)
	{
		return Arr::get($this->_settings, $key, $default);
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
		if ($this->cache_buster())
		{
			return $this->_location.'?cache='.time();
		}

		return $this->_location;
	}
	
	/**
	 * Get attributes.
	 *
	 * @return  array
	 */
	public function attributes()
	{
		return Arr::get($this->_settings, 'attributes', array());
	}

	public function cache_buster()
	{
		return Arr::get($this->_settings, 'cache_buster', FALSE);
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