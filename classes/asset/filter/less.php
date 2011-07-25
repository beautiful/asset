<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_Less extends Asset_Filter {

	protected $_types = array('less');
	
	public function __construct()
	{
		require_once dirname(__FILE__).'/../../../vendor/lessphp/lessc.inc.php';
	}

	protected function _filter(array $assets)
	{
		$final = array();
		$lc = new lessc();
		
		foreach ($assets as $_asset)
		{
			$css = $lc->parse($this->_asset_contents(array($_asset)));
			$location = $_asset->location();
			$css_location = "css/{$this->_get_filename($location)}.css";
			file_put_contents(DOCROOT.$css_location, $css);
			$final[] = new Asset_CSS($css_location);
		}
		
		return $final;
	}

}