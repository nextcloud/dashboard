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

namespace OCA\Dashboard\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;

class Application extends App {

	const APP_NAME = 'dashboard';

	/** @var IAppContainer */
	private $container;


	public function __construct(array $urlParams = array()) {
		parent::__construct(self::APP_NAME, $urlParams);
		$this->container = $this->getContainer();
	}


	/**
	 * Register Navigation Tab
	 */
	public function registerNavigation() {
		$this->container->getServer()
						->getNavigationManager()
						->add($this->dashboardNavigation());
	}


	/**
	 * @return array
	 */
	private function dashboardNavigation(): array {
		$urlGen = \OC::$server->getURLGenerator();
		$navName = \OC::$server->getL10N(self::APP_NAME)
							   ->t('Dashboard');

		return [
			'id'    => self::APP_NAME,
			'order' => -1,
			'href'  => $urlGen->linkToRoute('dashboard.Navigation.navigate'),
			'icon'  => $urlGen->imagePath(self::APP_NAME, 'dashboard.svg'),
			'name'  => $navName
		];
	}

}
