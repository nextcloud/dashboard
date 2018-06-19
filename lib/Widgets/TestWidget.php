<?php

namespace OCA\Dashboard\Widgets;


use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\IDashboardWidget;

class TestWidget implements IDashboardWidget {

	const WIDGET_ID = 'test1';


	/**
	 * @return string
	 */
	public function getId() {
		return self::WIDGET_ID;
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
	public function widgetSetup() {
		return [
			'template' =>
				[
					'app'      => Application::APP_NAME,
					'icon'     => 'icon-test',
					'navigate' => 'widgets/test'
				],
			'size'     => [
				'width' => 5,
				'height' => 2
			],
			'options'  => [
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
	public function loadWidget($config) {
	}


}