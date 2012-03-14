<?php
/**
 * Test all Assets
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Asset
 * @category    Asset Group
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2012
 * @license     MIT
 */
class AssetGroupTest extends PHPUnit_Framework_TestCase {

	public static function setUpBeforeClass()
	{
		Asset_Group_Mock::$config = array(
			'groups' => array(
				'core' => array(
					array('Mock', '/css/base.css'),
					array('Mock', '/js/app.js', array(
						'attributes' => array(
							'async' => TRUE,
						),
					)),
				),
			),
			'global_settings' => array(
				'attributes' => array(
					'test'     => TRUE,
					'test_str' => 'Testing',
				),
			),
		);
	}

	public function providerAssetGroups()
	{
		return array(
			array(
				'Asset_Group_Mock',
				array(
					new Asset_Mock('/css/base.css'),
					new Asset_Mock('/js/app.js'),
				),
				NULL,
				2,
			),
			array(
				'Asset_Group_Mock',
				'core',
				array(
					'global_settings' => array(
						'attributes' => array('data-test' => TRUE),
					),
				),
				2,
			),
			array(
				'Asset_Group_Mock',
				'core',
				array(
					'groups' => array(
						'core' => array(
							array('Mock', '/css/theme.css', array(
								'attributes' => array(
									'data-test' => TRUE,
								),
							)),
						),
					),
					'global_settings' => array(
						'cache_buster' => TRUE,
					),
				),
				3,
			),
		);
	}

	public function providerExpectedCounts()
	{
		return $this->providerAssetGroups();
	}
	
	/**
	 * @dataProvider  providerExpectedCounts
	 */
	public function testExpectedAssetCount(
		$class,
		$group,
		$config,
		$expected_count)
	{
		$group = new $class($group, $config);
		$this->assertSame($expected_count, count($group->as_array()));
	}

	public function providerExpectedConfigs()
	{
		static::setUpBeforeClass();
		$expected = array();

		foreach ($this->providerAssetGroups() as $_data_set)
		{
			if (is_string($_data_set[1]))
			{
				$expected_config =
					Asset_Group_Mock::$config['global_settings'];

				if (isset($_data_set[2]['global_settings']))
				{
					$expected_config = Arr::merge(
						$expected_config,
						$_data_set[2]['global_settings']);
				}
			}
			else
			{
				$expected_config = array();
			}

			$_data_set[3] = $expected_config;
			$expected[] = $_data_set;
		}
		
		return $expected;
	}

	/**
	 * @dataProvider  providerExpectedConfigs
	 */
	public function testConfigMerged(
		$class,
		$group_arg,
		$config,
		$expected_config)
	{
		$group = new $class($group_arg, $config);

		$expected = function ($attributes)
		{
			return print_r($attributes, TRUE);
		};

		$config = $group->config();

		foreach ($group->as_array() as $_k => $_asset)
		{
			$_asset->callback = $expected;

			$expected_attrs = Arr::get($expected_config, 'attributes', array());

			if (is_string($group_arg)
				AND isset($config['groups'][$group_arg][$_k][2]['attributes']))
			{
				$expected_attrs = Arr::merge(
					$expected_attrs,
					$config['groups'][$group_arg][$_k][2]['attributes']);
			}

			$this->assertSame(
				$expected($expected_attrs),
				(string) $_asset);

			$this->assertSame(
				isset($expected_config['cache_buster']),
				strpos($_asset->location(), 'cache') !== FALSE);
		}
	}

	/**
	 * @expectedException  InvalidArgumentException
	 */
	public function testInvalidGroupException()
	{
		new Asset_Group_Mock(1);
	}

}