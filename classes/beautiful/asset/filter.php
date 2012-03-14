<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Asset Filter
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Filter
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class Beautiful_Asset_Filter {

	// Filters can be fussy about types they affect
	protected static $_types = array();
	
	/**
	 * Get the assets that can be filtered by this filter.
	 *
	 * @param   array  of assets to filter
	 * @return  array  of assets that can be filtered
	 */
	public static function extract_filterable(array $assets)
	{
		if (empty(static::$_types))
		{
			return $assets;
		}
		
		$filterable = array();
		
		foreach ($assets as $_asset)
		{
			if (in_array($_asset->type(), static::$_types))
			{
				$filterable[] = $_asset;
			}
		}
	
		return $filterable;
	}
	
	/**
	 * Get an array of assets by type.
	 *
	 * @param   array  of assets to filter
	 * @return  array
	 */
	public static function split_by_type(array $assets)
	{
		$types = array();
		
		foreach ($assets as $_asset)
		{
			$types[$_asset->type()][] = $_asset;
		}
		
		return $types;
	}
	
	/**
	 * Filter assets.
	 *
	 * @return  array  of filtered assets
	 */
	public static function filter(array $assets)
	{
		$filterable = static::extract_filterable($assets);
		$non_filterable = array_diff($assets, $filterable);
		$final = array();
		
		if ( ! empty($filterable))
		{
			$final = array_merge($final, static::_filter($filterable));
		}
		
		if ( ! empty($non_filterable))
		{
			$final = array_merge($final, $non_filterable);
		}
		
		return $final;
	}
	
	// Inner workings
	protected static function _filter(array $assets)
	{
		throw new Exception('Not implemented');
	}
	
	// Get filename for a location
	protected static function _get_filename($location)
	{
		$filename = basename($location);
		return substr($filename, 0, strrpos($filename, '.'));
	}
	
	// Get asset contents
	protected static function _asset_contents(array $assets)
	{
		ob_start();
		
		foreach ($assets as $_asset)
		{
			$location = $locations[] = $_asset->location();
			
			if (strpos($location, '://') === FALSE)
			{
				$url = URL::base(TRUE).$location;
			}
			else
			{
				$url = $location;
			}
			
			echo Request::factory($url)
				->execute()
				->body();
			
			echo PHP_EOL;
		}
		
		return ob_get_clean();
	}

}