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

namespace OCA\Dashboard\AppInfo;

use OC\Files\View;
use OCA\Activity\Controller\Activities;
use OCA\Activity\Controller\APIv1;
use OCA\Activity\Controller\Feed;
use OCA\Activity\Controller\Settings;
use OCA\Activity\ViewInfoCache;
use OCA\Dashboard\Controller\AdminController;
use OCA\DAV\Connector\Sabre\Principal;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;

class Application extends App {
	public function __construct(array $urlParams = array()) {
		parent::__construct('dashboard', $urlParams);
		$container = $this->getContainer();

		// Aliases for the controllers so we can use the automatic DI
		$container->registerAlias('OCA\Activity\ActivitiesController', Activities::class);
		$container->registerAlias('OCA\Activity\APIv1Controller', APIv1::class);
		$container->registerAlias('OCA\Activity\FeedController', Feed::class);
		$container->registerAlias('OCA\Activity\SettingsController', Settings::class);
		$container->registerAlias(
			'OCA\OCA\Dashboard\Controller\AdminController', AdminController::class
		);

		$container->registerService(
			'OCA\Activity\ViewInfoCache', function(IAppContainer $c) {
			return new ViewInfoCache(
				new View('')
			);
		}
		);
		$container->registerService(
			'OCA\Activity\isCLI', function() {
			return \OC::$CLI;
		}
		);
		$container->registerService(
			'OCP\IUserManager', function(IAppContainer $c) {
			return $c->getServer()
					 ->getUserManager();
		}
		);
		$container->registerService(
			'Symfony\Component\EventDispatcher\EventDispatcherInterface',
			function(IAppContainer $c) {
				return $c->getServer()
						 ->getEventDispatcher();
			}
		);
		$container->registerService(
			'OCP\Security\ISecureRandom', function(IAppContainer $c) {
			return $c->getServer()
					 ->getSecureRandom();
		}
		);
		$container->registerService(
			'CurrentUID', function(IAppContainer $c) {
			$user = $c->getServer()
					  ->getUserSession()
					  ->getUser();

			return ($user) ? $user->getUID() : '';
		}
		);
		$container->registerService(
			'OCP\IDBConnection', function(IAppContainer $c) {
			return $c->getServer()
					 ->getDatabaseConnection();
		}
		);
		$container->registerService(
			'OCA\DAV\Connector\Sabre\Principal', function(IAppContainer $c) {
			return new Principal(
				$c->getServer()
				  ->getUserManager(),
				$c->getServer()
				  ->getGroupManager()
			);
		}
		);

	}
}