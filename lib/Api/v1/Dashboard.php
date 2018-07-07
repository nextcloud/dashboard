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

namespace OCA\Dashboard\Api\v1;


use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\Model\WidgetSettings;
use OCA\Dashboard\Service\ConfigService;
use OCA\Dashboard\Service\EventsService;
use OCA\Dashboard\Service\WidgetsService;
use OCP\AppFramework\QueryException;

class Dashboard {

	const API_VERSION = [0, 1, 0];

	protected static function getContainer() {
		$app = new Application();

		return $app->getContainer();
	}


	/**
	 * returns app name
	 *
	 * @return string
	 */
	public static function appName() {
		return Application::APP_NAME;
	}


	/**
	 * FullTextSearch::version();
	 *
	 * returns the current version of the API
	 *
	 * @return array
	 * @throws QueryException
	 */
	public static function version() {
		$c = self::getContainer();

		return [
			[
				'fulltextsearch' => $c->query(ConfigService::class)
									  ->getAppValue('installed_version')
			],
			['api' => self::API_VERSION]
		];
	}


	/**
	 * @param string $widgetId
	 * @param string $userId
	 *
	 * @return WidgetSettings
	 * @throws QueryException
	 */
	public static function getWidgetSettings($widgetId, $userId) {
		$c = self::getContainer();

		return $c->query(WidgetsService::class)
				 ->getWidgetSettings($widgetId, $userId);
	}


	/**
	 * @param string $widgetId
	 * @param string $userId
	 * @param array $payload
	 *
	 * @throws QueryException
	 */
	public static function createEvent($widgetId, $userId, $payload) {
		$c = self::getContainer();

		$c->query(EventsService::class)
		  ->createEvent($userId, $widgetId, $payload);
	}


}