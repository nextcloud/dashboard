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
 * @contributor tuxedo-rb | TUXEDO Computers GmbH | https://www.tuxedocomputers.com
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

use OCA\Dashboard\Controller\AdminController;
use OCA\DAV\Connector\Sabre\Principal;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;

class Application extends App {

	public function __construct(array $urlParams = array()) {
		parent::__construct('dashboard', $urlParams);
		$container = $this->getContainer();

		// Aliases for the controllers so we can use the automatic DI
		$container->registerAlias(
			'AdminController',
			AdminController::class
		);
		$container->registerService(
			'OCP\IUserManager',
			function(IAppContainer $c) {
				return $c->getServer()->getUserManager();
			}
		);
		$container->registerService(
			'Symfony\Component\EventDispatcher\EventDispatcherInterface',
			function(IAppContainer $c) {
				return $c->getServer()->getEventDispatcher();
			}
		);
		$container->registerService(
			'OCP\Security\ISecureRandom',
			function(IAppContainer $c) {
				return $c->getServer()->getSecureRandom();
			}
		);
		$container->registerService(
			'CurrentUID',
			function(IAppContainer $c) {
				$user = $c->getServer()->getUserSession()->getUser();
				return ($user) ? $user->getUID() : '';
			}
		);
		$container->registerService(
			'OCP\IDBConnection',
			function(IAppContainer $c) {
				return $c->getServer()->getDatabaseConnection();
			}
		);
		$container->registerService(
			'OCA\DAV\Connector\Sabre\Principal',
			function(IAppContainer $c) {
				return new Principal(
					$c->getServer()->getUserManager(),
					$c->getServer()->getGroupManager(),
					$c->getServer()->getShareManager(),
					$c->getServer()->getUserSession()
				);
			}
		);
	}

}
