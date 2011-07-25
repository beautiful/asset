<?php defined('SYSPATH') or die('No direct script access.');
require_once dirname(__FILE__).'/../../../vendor/jsminplus/jsminplus.php';

/**
 * Beautiful Asset JsMinPlus Filter.
 *
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Filter
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Asset_Filter_JsMinPlus extends Asset_Filter {

	protected static $_types = array('js');

	protected static function _filter(array $assets)
	{
		$final = array();
		
		foreach ($assets as $_asset)
		{
			$minified = JSMinPlus::minify(self::_asset_contents(
				array($_asset)));
			$filename = self::_get_filename($_asset->location());
			$js_location = "js/{$filename}.min.js";
			file_put_contents(DOCROOT.$js_location, $minified);
			$final[] = new Asset_JS($js_location);
		}
		
		return $final;
	}

}