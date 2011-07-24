<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_JsMinPlus extends Asset_Filter {

	protected $_types = array('js');
	
	public function __construct()
	{
		require_once dirname(__FILE__).'/../../../vendor/jsminplus/jsminplus.php';
	}

	protected function _filter(array $assets)
	{
		$final = array();
		
		foreach ($assets as $_asset)
		{
			$minified = JSMinPlus::minify($this->_asset_contents(
				array($_asset)));
			$filename = $this->_get_filename($_asset->location());
			$js_location = "js/{$filename}.min.js";
			file_put_contents(DOCROOT.$js_location, $minified);
			
			$final[] = new Asset_JS($js_location);
		}
		
		return $final;
	}

}