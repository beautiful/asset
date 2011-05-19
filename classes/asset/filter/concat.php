<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_Concat extends Asset_Filter {

	protected function _filter(array $assets)
	{
		$filtered = array();
		$asset_types = $this->split_by_type($assets);
		
		foreach ($asset_types as $_type => $_assets)
		{
			$locations =
			$contents = array();
			
			foreach ($_assets as $_asset)
			{
				$location = $locations[] = $_asset->location();
				$contents[] = Request::factory($location)
					->execute()
					->body();
			}
			
			$md5 = md5(implode('', $locations).time());
			$location = "{$_type}/compiled-{$md5}.{$_type}";
			
			file_put_contents(DOCROOT.$location, implode("\n", $contents));
			
			$class = "Asset_{$_type}";
			
			$filtered[] = new $class($location);
		}
		
		return $filtered;
	}

}