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

	// Map of config
	protected $_config;

	// Group name or list of assets
	protected $_group;
	
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
	public function __construct($group = NULL, array $additional_config = NULL)
	{
		$this->group($group);

		if ($additional_config !== NULL)
		{
			$this->merge_config($additional_config);
		}
	}

	/**
	 * Set/get group.
	 *
	 * @param   string|array
	 * @return  string|array
	 */
	protected function group($group = NULL)
	{
		if ($group === NULL)
		{
			return $this->_group;
		}
		else if (is_string($group) OR is_array($group))
		{
			$this->_group = $group;
		}
		else
		{
			throw new InvalidArgumentException(
				'Asset Group must be array of Assets or a config path.');
		}
	}

	/**
	 * Merge new configuration.
	 *
	 * @param   array
	 * @return  $this
	 */
	protected function merge_config(array $additional_config)
	{
		$config = $this->config();

		if (isset($config['groups'], $additional_config['groups']))
		{
			$config['groups'] = array_merge_recursive(
				$config['groups'],
				$additional_config['groups']);
		}

		$this->_config = Arr::merge($config, $additional_config);
	}
	
	/**
	 * Load config for a group.
	 *
	 * @return  Config
	 */
	protected function load_config()
	{
		return Kohana::$config->load('assets')->as_array();
	}

	/**
	 * Get entire config or a single key.
	 *
	 * @param   string
	 * @return  mixed
	 */
	protected function config($key = NULL)
	{
		if ($this->_config === NULL)
		{
			$this->_config = $this->load_config();
		}

		if ($key === NULL)
		{
			return $this->_config;
		}
		
		return Arr::get($this->_config, $key);
	}

	/**
	 * Build Asset objects from config.
	 *
	 * @param   array  list of lists ($type, $location)
	 * @param   array  global settings to be shared by assets
	 * @return  array  list of Asset's
	 */
	protected function assets_from_array(array $array)
	{
		$assets = array();

		$global_settings = $this->config('global_settings');

		foreach ($array as $_asset)
		{
			$class = "Asset_{$_asset[0]}";

			$settings = $global_settings;

			if (isset($_asset[2]))
			{
				$settings = Arr::merge($settings, $_asset[2]);
			}
			
			$assets[] = new $class($_asset[1], $settings);
		}

		return $assets;
	}

	/**
	 * Get assets.
	 *
	 * @return  array
	 */
	protected function assets()
	{
		$group = $this->group();

		if (is_string($group))
		{
			$group_name = $group;
			$group = Arr::get($this->config('groups'), $group_name);

			if ($group === NULL)
			{
				throw new Kohana_Exception(
					'Group :group not found in configuration.',
					array(':group' => $group_name));
			}

			return $this->assets_from_array($group);
		}
		else
		{
			return $group;
		}
	}

	/**
	 * Render as array.
	 *
	 * @return  array
	 */
	public function as_array()
	{
		return $this->assets();
	}
	
	/**
	 * Get HTML for including all assets in group.
	 *
	 * @return  string
	 */
	public function html()
	{
		return implode(PHP_EOL, $this->as_array()).PHP_EOL;
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
