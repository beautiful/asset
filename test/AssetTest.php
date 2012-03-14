<?php
/**
 * Test all Assets
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2012
 * @license     MIT
 */
class AssetTest extends PHPUnit_Framework_TestCase {
	
	public function providerAssets()
	{
		return array(
			array('Asset_Coffee',     'coffee', '/js/app.coffee'),
			array('Asset_CSS',        'css',    '/css/screen.css'),
			array('Asset_JS',         'js',     '/js/app.js'),
			array('Asset_LESS',       'less',   '/css/screen.less'),
			array('Asset_Mock',       'mock',   '/mock'),
			array('Asset_CustomMock', 'mock',   '/mock'),
		);
	}

	/**
	 * General smoke test for all supported Asset types.
	 *
	 * @dataProvider  providerAssets
	 */
	public function testAsset($class, $type, $location)
	{
		$asset = new $class($location, array('test' => TRUE));
		$this->assertInstanceOf('Asset', $asset);
		$this->assertSame($location, $asset->location());
		$this->assertSame($type, $asset->type());
	}

	/**
	 * Test cache buster.
	 */
	public function testCacheBuster()
	{
		$asset = new Asset_Mock('/mock', array(
			'cache_buster' => TRUE,
			'attributes'   => array(
				'mock' => TRUE,
			),
		));
		$this->assertTrue(strpos($asset->location(), '?cache=') !== FALSE);

		return $asset;
	}

	/**
	 * Test ::__toString().
	 *
	 * @depends  testCacheBuster
	 */
	public function testToString($asset)
	{
		$expected = $asset->callback = function ($attributes)
		{
			return print_r($attributes, TRUE);
		};
		
		$this->assertSame(
			$expected(array('mock' => TRUE)),
			(string) $asset);

		return $asset;
	}

	// /**
	//  * Test ::__toString() handles thrown exception.
	//  *
	//  * @dataProvider  providerAssetMock
	//  */
	// public function testToStringException($asset)
	// {
	// 	$asset->callback = function ($attributes)
	// 	{
	// 		throw new Exception('Testing');
	// 	};

	// 	ob_start();
	// 	$html = (string) $asset;
	// 	ob_end_clean();

	// 	$this->assertTrue(is_string($html));
	// 	$this->assertTrue(empty($html));
	// }

}