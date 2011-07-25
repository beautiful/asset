<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Asset Concatenation Filter.
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Filter
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Asset_Filter_Concat extends Asset_Filter {

	protected static function _filter(array $assets)
	{
		$filtered = array();
		$asset_types = self::split_by_type($assets);
		
		foreach ($asset_types as $_type => $_assets)
		{
			$locations = array();
			$contents = self::_asset_contents($_assets);
			$md5 = md5($contents.time());
			$location = "{$_type}/compiled-{$md5}.{$_type}";
			file_put_contents(DOCROOT.$location, $contents);
			$class = "Asset_{$_type}";
			$filtered[] = new $class($location);
		}
		
		return $filtered;
	}

}