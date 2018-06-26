<?php

namespace OCA\Dashboard\Widgets;


use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetRequest;

class Test1Widget implements IDashboardWidget {

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
	 * @return string
	 */
	public function getDescription() {
		return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor '
			   . ' incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam';
	}


	/**
	 * @return array
	 */
	public function getTemplate() {
		return [
			'app'     => Application::APP_NAME,
			'icon'    => 'icon-lorem',
			'css'     => 'widgets/test1',
			'content' => 'widgets/Test1'
		];
	}


	/**
	 * @return array
	 */
	public function widgetSetup() {
		return [
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
			'settings' => [
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


	/**
	 * @param WidgetRequest $request
	 */
	public function requestWidget(WidgetRequest $request) {
	}

}