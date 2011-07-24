<?php defined('SYSPATH') or die('No direct script access.');

abstract class Asset_Filter {

	protected $_types = array();
	
	public function extract_filterable(array $assets)
	{
		if (empty($this->_types))
		{
			return $assets;
		}
		
		$filterable = array();
		
		foreach ($assets as $_asset)
		{
			if (in_array($_asset->type(), $this->_types))
			{
				$filterable[] = $_asset;
			}
		}
	
		return $filterable;
	}
	
	public function split_by_type(array $assets)
	{
		$types = array();
		
		foreach ($assets as $_asset)
		{
			$types[$_asset->type()][] = $_asset;
		}
		
		return $types;
	}
	
	public function filter(array $assets)
	{
		$filterable = $this->extract_filterable($assets);
		$non_filterable = array_diff($assets, $filterable);
		$final = array();
		
		if ( ! empty($filterable))
		{
			$final = array_merge($final, $this->_filter($filterable));
		}
		
		if ( ! empty($non_filterable))
		{
			$final = array_merge($final, $non_filterable);
		}
		
		return $final;
	}
	
	abstract protected function _filter(array $assets);
	
	protected function _get_filename($location)
	{
		$filename = basename($location);
		return substr($filename, 0, strrchr('.', $filename));
	}
	
	protected function _asset_contents(array $assets)
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