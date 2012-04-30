<?php
/**
 * Test bootstrap for Beautiful Asset
 *
 * You will find a number of Mock (but real) objects used in
 * various tests. In the future I plan to place these in their
 * own directory.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2012
 * @license     MIT
 */
define('EXT', '.php');
define('APPPATH', __DIR__.'/test/');
define('SYSPATH', __DIR__.'/test/system/');
// define('DOCROOT', __DIR__.'/test/');
// define('MODPATH', __DIR__.'/test/');

error_reporting(E_ALL | E_STRICT);
require SYSPATH.'classes/Kohana/Core.php';
require SYSPATH.'classes/Kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::$config = new Kohana_Config;
Kohana::$config->attach(new Config_File);

Kohana::modules(array('beautiful-asset' => __DIR__.'/'));

class Asset_Mock extends Asset {

	protected $_type = 'mock';

	public $callback;

	public function html()
	{
		if ($this->callback === NULL)
		{
			$this->callback = function () { return ''; };
		}

		return call_user_func($this->callback, $this->attributes());
	}

}

class Asset_CustomMock extends Asset_Mock {

	protected $_settings = array(
		'attributes' => array(
			'data-custom' => TRUE,
		),
	);

}

class Asset_Group_Mock extends Asset_Group {

	public static $config = array();

	protected function load_config()
	{
		return static::$config;
	}

	public function config($key = NULL)
	{
		return parent::config($key);
	}

}