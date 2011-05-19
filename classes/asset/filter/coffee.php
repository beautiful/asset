<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_Coffee extends Asset_Filter {

	/**
	 * @see https://github.com/DanHulton/kohana-coffeescript
	 */
	public static function compile($coffee_location)
	{
		exec(strtr(Kohana::config('assets.filter_options.coffeecmd'), array(':src' => $coffee_location)), $output);

		// First line is wrapper only on successful compile
		if ("(function() {" !== $output[0]) {
			// If unsuccessful, first line is error message
			throw new Kohana_Exception($output[0]);
		}

		return implode("\n", $output);
	}

	protected $_types = array('coffee');

	protected function _filter(array $assets)
	{
		$final = array();
		
		foreach ($assets as $_asset)
		{
			$location = $_asset->location();
			$filename = $this->_get_filename($location);
			$coffee_file_path = DOCROOT."coffee/{$filename}.coffee";
			
			// Grab contents by HTTP and put into coffee file
			file_put_contents(
				$coffee_file_path,
				Request::factory($location)
					->execute()
					->body()
			);
			
			// Now compile to JS
			$js_location = "js/{$filename}.js";
			file_put_contents(DOCROOT.$js_location, self::compile($coffee_file_path));
				
			$final[] = new Asset_JS($js_location);
		}
		
		
		return $final;
	}

}
