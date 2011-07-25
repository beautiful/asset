<?php defined('SYSPATH') or die('No direct script access.');

class Beautiful_Asset_Cache {

	public static function group($group)
	{
		return new Asset_Cache(new Asset_Group($group));
	}

	protected $_group;
	
	protected $_cache;
	
	protected $_cache_name;

	public function __construct(Beautiful_Asset_Group $group)
	{
		$this->_group = $group;
		$this->_cache_name();
	}
	
	protected function _asset_locations()
	{
		$locations = array();
		
		foreach ($this->_group->assets() as $_asset)
		{
			$locations[] = $_asset->location();
		}
		
		return $locations;
	}
	
	protected function _cache_name()
	{
		if ($this->_cache_name === NULL)
		{
			$this->_cache_name = md5(implode('',
				$this->_asset_locations()));
		}
		
		return $this->_cache_name;
	}
	
	public function should_cache()
	{
		return Kohana::$environment === Kohana::PRODUCTION;
	}
	
	public function cache()
	{
		if ($this->_cache === NULL)
		{
			$this->_cache = Kohana::cache($this->_cache_name(),
				NULL, 9999999999);
		}
		
		return $this->_cache;
	}
	
	public function is_cached()
	{
		return (boolean) $this->cache();
	}
	
	public function generate_cache()
	{
		$this->_cache = $this->_group->html();
		Kohana::cache($this->_cache_name(),
			$this->_cache, 9999999999);
	}
	
	public function html()
	{
		if ($this->should_cache())
		{
			if ( ! $this->is_cached())
			{
				$this->generate_cache();
			}
			
			return $this->cache();
		}
		
		return $this->_group->html();
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