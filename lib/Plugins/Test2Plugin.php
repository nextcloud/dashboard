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

namespace OCA\Dashboard\Plugins;


use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\IDashboardPlugin;
use OCP\AppFramework\Http\TemplateResponse;

class Test2Plugin implements IDashboardPlugin {

	const TEST_PLUGIN_ID = 'test2';


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
		return 'Test 2';
	}


	/**
	 * @return array
	 */
	public function getTemplate() {
		return [
			'app'  => Application::APP_NAME,
			'name' => 'test/plugin'
		];
	}


	/**
	 * @return array
	 */
	public function pluginSetup() {
		return [
			'template' => [
				'app'  => Application::APP_NAME,
				'name' => 'test/plugin'
			],
			'options'  => [
				[
					'name'        => 'test_value_1',
					'title'       => 'Test Option 1',
					'type'        => 'input',
					'default'     => '',
					'size'        => 'large',
					'placeholder' => 'txt'
				],
				[
					'name'    => 'test_value_1',
					'title'   => 'Test Option 2',
					'default' => '',
					'type'    => 'checkbox'
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