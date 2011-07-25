<?php defined('SYSPATH') or die('No direct script access.');
require_once dirname(__FILE__).'/../../../vendor/lessphp/lessc.inc.php';

/**
 * Beautiful Asset Less Filter.
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Filter
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Asset_Filter_Less extends Asset_Filter {

	protected static $_types = array('less');

	protected static function _filter(array $assets)
	{
		$final = array();
		$lc = new lessc();
		
		foreach ($assets as $_asset)
		{
			$css = $lc->parse(self::_asset_contents(array($_asset)));
			$location = self::_get_filename($_asset->location());
			$css_location = "css/{$location}.css";
			file_put_contents(DOCROOT.$css_location, $css);
			$final[] = new Asset_CSS($css_location);
		}
		
		return $final;
	}

}