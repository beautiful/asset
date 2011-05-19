<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Filter_JsHint extends Asset_Filter {

	public static function test($js_location, $path)
	{
		$command = strtr(
			Kohana::config('assets.filter_options.jshintcmd'),
			array(
				':path' => $path,
				':src' => $js_location
			)
		);
	
		exec($command, $output, $return);

		// First line is wrapper only on successful compile
		if ($return !== 0)
		{
			// If unsuccessful, first line is error message
			throw new Kohana_Exception(implode("\n", $output));
		}
	}

	protected $_types = array('js');
	
	protected $_path = NULL;
	
	public function __construct()
	{
		$this->_path = realpath('../../../vendor/jshint');
	}

	protected function _filter(array $assets)
	{
		foreach ($assets as $_asset)
		{
			self::test(
				Request::factory($_asset->location())
					->execute()
					->body()
			);
		}
		
		return $assets;
	}

}