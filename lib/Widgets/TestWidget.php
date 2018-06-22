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
		return 'Lorem Ipsum';
	}


	/**
	 * @return array
	 */
	public function widgetSetup() {
		return [
			'template' => [
				'app'  => Application::APP_NAME,
				'icon' => 'icon-lorem',
				'name' => 'widgets/Test'
			],
			'size'     => [
				'min'     => [
					'width'  => 4,
					'height' => 3
				],
				'default' => [
					'width'  => 6,
					'height' => 4
				],
				'max'     => [
					'width'  => 10,
					'height' => 6
				]
			],
			'settings'  => [
				[
					'name'        => 'test_input',
					'title'       => 'IMAP address',
					'type'        => 'input',
					'placeholder' => 'imap.example.net'
				],
				[
					'name'        => 'test_input',
					'title'       => 'Login',
					'type'        => 'input',
					'placeholder' => 'username'
				],
				[
					'name'        => 'test_input',
					'title'       => 'Password',
					'type'        => 'input',
					'placeholder' => ''
				],
				[
					'name'    => 'test_long_lorem',
					'title'   => 'Longer Lorem',
					'type'    => 'checkbox',
					'default' => true
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