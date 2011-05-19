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
			throw new Kohana_Exception('Asset Group must be array of Assets or a config path.');
		}
	}
	
	public function assets()
	{
		return $this->_assets;
	}
	
	public function load_config($config_path)
	{
		$assets = Kohana::config("assets.groups.{$config_path}");
		$filters = Kohana::config("assets.groups.{$config_path}");
		
		foreach ($assets as $_asset)
		{
			$class = "Asset_{$_asset[0]}";
			$this->_assets[] = new $class($_asset[1]);
		}
		
		foreach ($filters as $_filter)
		{
			$class = "Asset_Filter_{$_filter[0]}";
			$this->add_filter(new $class);
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
		return implode("\n", $this->assets());
	}

}