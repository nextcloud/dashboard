<?php
/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 * @copyright regio iT 2017
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
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetRequest;
use OCA\Dashboard\Model\WidgetSettings;

class Test3Widget implements IDashboardWidget {

	const WIDGET_ID = 'test3';


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
		return 'At vero eos';
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti';
	}


	/**
	 * @return array
	 */
	public function getTemplate() {
		return [
			'app'     => Application::APP_NAME,
			'icon'    => 'icon-lorem',
			'css'     => 'widgets/test3',
			'content' => 'widgets/Test3'
		];
	}


	/**
	 * @return array
	 */
	public function widgetSetup() {
		return [
			'size'     => [
				'min'     => [
					'width'  => 3,
					'height' => 4
				],
				'default' => [
					'width'  => 5,
					'height' => 5
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
	 * @param WidgetSettings $settings
	 */
	public function loadWidget($settings) {
	}


	/**
	 * @param WidgetRequest $request
	 */
	public function requestWidget(WidgetRequest $request) {
	}


}