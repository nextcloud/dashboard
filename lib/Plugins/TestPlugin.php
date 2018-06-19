<?php

namespace OCA\Dashboard\Plugins;


use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\IDashboardPlugin;
use OCP\Util;

class TestPlugin implements IDashboardPlugin {

	const TEST_PLUGIN_ID = 'test1';


	/**
	 * @return string
	 */
	public function getId() {
		return self::TEST_PLUGIN_ID;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return 'Test 1';
	}


	/**
	 * @return array
	 */
	public function getTemplate() {
		return [
			'app'      => Application::APP_NAME,
			'icon'     => 'icon-test',
			'navigate' => 'plugins/test'
		];
	}


	/**
	 * @return array
	 */
	public function pluginSetup() {
		return [
			'options' => [
				[
					'name'        => 'test_value_1',
					'title'       => 'Test Option 1',
					'type'        => 'input',
					'size'        => 'large',
					'placeholder' => 'txt'
				],
				[
					'name'  => 'test_value_1',
					'title' => 'Test Option 2',
					'type'  => 'checkbox'
				]
			]
		];
	}


	/**
	 * @param array $config
	 */
	public function loadPlugin($config) {
	}


}