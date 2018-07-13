<?php declare(strict_types=1);


/**
 * Nextcloud - Dashboard app
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018, Maxence Lange <maxence@artificial-owl.com>
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Dashboard\Widgets;


use OCA\Dashboard\AppInfo\Application;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetSettings;

class Test1Widget implements IDashboardWidget {

	const WIDGET_ID = 'test1';


	/**
	 * @return string
	 */
	public function getId(): string {
		return self::WIDGET_ID;
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return 'Lorem Ipsum';
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor '
			   . ' incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam';
	}


	/**
	 * @return array
	 */
	public function getTemplate(): array {
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
	public function widgetSetup(): array {
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
	 * @param IWidgetSettings $settings
	 */
	public function loadWidget(IWidgetSettings $settings) {
	}


	/**
	 * @param IWidgetRequest $request
	 */
	public function requestWidget(IWidgetRequest $request) {
	}

}