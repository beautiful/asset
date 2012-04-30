<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Asset Group
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Group
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Beautiful_Asset_Group {

	// List of assets in group
	protected $_assets = array();
	
	// List of filters to apply to group
	protected $_filters = array();

	/**
	 * Create new instance of Beautiful_Asset_Group.
	 *
	 * @param   mixed  if string used to as config path
	 *                 if array used as list of Beautiful_Asset
	 * @return  void
	 * @throws  InvalidArgumentException
	 */
	public function __construct($groups)
	{
		if (is_string($groups))
		{
			$this->load_config($groups);
		}
		else if (is_array($groups))
		{
			$this->_assets = $groups;
		}
		else
		{
			throw new InvalidArgumentException(
				'Asset Group must be array of Assets or a config path.');
		}
	}
	
	/**
	 * Get assets.
	 *
	 * @return  array
	 */
	public function assets()
	{
		return $this->_assets;
	}
	
	/**
	 * Load config for a group.
	 *
	 * @param   string  config path
	 * @return  $this
	 * @throws  UnexpectedValueException
	 */
	public function load_config($config_path)
	{
		$config = Kohana::$config->load('assets');
		$assets = Arr::get($config['groups'], $config_path);
		$global_settings = Arr::get($config, 'global_settings', array());
		
		if ( ! $assets)
		{
			throw new UnexpectedValueException(
				'You have no assets defined for '.$config_path);
		}
		
		foreach ($assets as $_asset)
		{
			$class = "Asset_{$_asset[0]}";

			$settings = $global_settings;
			
			if (isset($_asset[2]))
			{
				$settings = Arr::merge($settings, $_asset[2]);
			}
			
			$this->_assets[] = new $class($_asset[1], $settings);
		}
		
		if (isset($config['filters']))
		{
			$filters = Arr::get($config['filters'], $config_path);
			
			if ( ! empty($filters))
			{
				foreach ($filters as $_filter)
				{
					$this->add_filter($_filter);
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * Add filter to group.
	 *
	 * @param   string
	 * @return  $this
	 */
	public function add_filter($filter)
	{
		$this->_filters[] = $filter;
		return $this;
	}
	
	/**
	 * Apply filters to group.
	 *
	 * @return  $this
	 */
	public function filter()
	{
		foreach ($this->_filters as $_filter)
		{
			$method = "Asset_Filter_{$_filter}::filter";
			$this->_assets = call_user_func($method, $this->assets());
		}
		
		return $this;
	}
	
	/**
	 * Get HTML for including all assets in group.
	 *
	 * @return  string
	 */
	public function html()
	{
		$this->filter();
		return implode(PHP_EOL, $this->assets()).PHP_EOL;
	}
	
	/**
	 * toString uses Beautiful_Asset_Group::html().
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
