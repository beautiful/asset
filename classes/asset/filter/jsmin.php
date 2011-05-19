<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_JsMin extends Asset_Filter {

	protected $_types = array('js');
	
	public function __construct()
	{
		require_once '../../../vendor/jsmin-php/jsmin.php';
	}

	protected function _filter(array $assets)
	{
		$final = array();
		
		foreach ($assets as $_asset)
		{
			$location = $_asset->location();
			
			$minified = JSMin::minify(
				Request::factory($location)
					->execute()
					->body()
			);
			
			$js_location = "js/{$this->_get_filename($location)}.min.js";
			
			file_put_contents(DOCROOT.$js_location, $minified);
			
			$final[] = new Asset_JS($js_location);
		}
		
		
		return $final;
	}

}