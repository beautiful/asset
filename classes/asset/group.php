<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Group {

	protected $_assets = array();
	
	protected $_filters = array();

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
	
	public function assets()
	{
		return $this->_assets;
	}
	
	public function load_config($config_path)
	{
		$config = Kohana::config('assets');
		$assets = Arr::get($config['groups'], $config_path);
		
		if ( ! $assets)
		{
			throw new UnexpectedValueException(
				'You have no assets defined for :group',
				array(':group' => $config_path));
		}
		
		foreach ($assets as $_asset)
		{
			$class = "Asset_{$_asset[0]}";
			
			if (isset($_asset[2]))
			{
				$this->_assets[] = new $class($_asset[1], $_asset[2]);
			}
			else
			{
				$this->_assets[] = new $class($_asset[1]);
			}
		}
		
		if (isset($config['filters']))
		{
			$filters = Arr::get($config['filters'], $config_path);
			
			if ( ! empty($filters))
			{
				foreach ($filters as $_filter)
				{
					$class = "Asset_Filter_{$_filter[0]}";
					$this->add_filter(new $class);
				}
			}
		}
		
		return $this;
	}
	
	public function add_filter(Asset_Filter $filter)
	{
		$this->_filters[] = $filter;
		return $this;
	}
	
	public function filter()
	{
		foreach ($this->_filters as $_filter)
		{
			$this->_assets = $_filter->filter($this->assets());
		}
		
		return $this;
	}
	
	public function html()
	{
		$this->filter();
		return implode("\n", $this->assets())."\n";
	}
	
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