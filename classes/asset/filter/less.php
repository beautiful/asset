<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_Less extends Asset_Filter {

	protected $_types = array('coffee');
	
	public function __construct()
	{
		require_once '../../../vendor/lessphp/less.inc.php';
	}

	protected function _filter(array $assets)
	{
		$final = array();
		$lc = new lessc();
		
		foreach ($assets as $_asset)
		{
			$location = $_asset->location();
			
			$css = $lc->parse(
				Request::factory($location)
					->execute()
					->body()
			);
			
			$css_location = "css/{$this->_get_filename($location)}.css";
			
			file_put_contents(DOCROOT.$css_location, $css);
			
			$final[] = new Asset_CSS($css_location);
		}
		
		
		return $final;
	}

}