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
	public function __construct($location, array $settings = NULL)
	{
		$this->_location = $location;

		if ($settings !== NULL)
		{
			if ($this->_settings)
			{
				$this->_settings = Arr::merge($this->_settings, $settings);
			}
			else
			{
				$this->_settings = $settings;
			}			
		}
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
		$location = $this->_location;

		if ($this->setting('cache_buster'))
		{
			$location .= '?cache='.time();
		}

		return $location;
	}
	
	/**
	 * Get attributes.
	 *
	 * @return  array
	 */
	protected function attributes()
	{
		return $this->setting('attributes', array());
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