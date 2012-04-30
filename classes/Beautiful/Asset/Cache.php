<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Asset Cache
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Cache
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Beautiful_Asset_Cache {

	/**
	 * Shortcut static method for caching a group.
	 *
	 * @param   mixed  if string used to as config path
	 *                 if array used as list of Beautiful_Asset
	 * @return  Asset_Cache
	 */
	public static function group($group)
	{
		return new Asset_Cache(new Asset_Group($group));
	}

	// Group we are caching
	protected $_group;
	
	// Cache contents
	protected $_cache;
	
	// Cache name
	protected $_cache_name;

	/**
	 * Create new instance of Asset_Cache.
	 *
	 * @param   Beautiful_Asset_Group
	 * @return  void
	 */
	public function __construct(Beautiful_Asset_Group $group)
	{
		$this->_group = $group;
		$this->_cache_name();
	}
	
	// Get asset locations as array
	protected function _asset_locations()
	{
		$locations = array();
		
		foreach ($this->_group->assets() as $_asset)
		{
			$locations[] = $_asset->location();
		}
		
		return $locations;
	}
	
	// Create/get cache name
	protected function _cache_name()
	{
		if ($this->_cache_name === NULL)
		{
			$this->_cache_name = md5(implode('', $this->_asset_locations()));
		}
		
		return $this->_cache_name;
	}
	
	/**
	 * Should we be caching?
	 *
	 * @return  boolean
	 */
	public function should_cache()
	{
		return Kohana::$environment === Kohana::PRODUCTION;
	}
	
	/**
	 * Get cache contents.
	 *
	 * @return  string
	 * @uses    Kohana::cache()
	 */
	public function cache()
	{
		if ($this->_cache === NULL)
		{
			$this->_cache = Kohana::cache($this->_cache_name(),
				NULL, 9999999999);
		}
		
		return $this->_cache;
	}
	
	/**
	 * Is this group cached already?
	 *
	 * @return  boolean
	 */
	public function is_cached()
	{
		return (boolean) $this->cache();
	}
	
	/**
	 * Generate cache contents.
	 *
	 * @return  string
	 * @uses    Kohana::cache()
	 */
	public function generate_cache()
	{
		$this->_cache = $this->_group->html();
		Kohana::cache($this->_cache_name(),
			$this->_cache, 9999999999);
	}
	
	/**
	 * Get group include HTML sometimes via cached methods.
	 *
	 * @return  string
	 */
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
	
	/**
	 * __toString uses Beautiful_Asset_Cache::html().
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