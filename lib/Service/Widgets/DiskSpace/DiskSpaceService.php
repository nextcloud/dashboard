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

namespace OCA\Dashboard\Service\Widgets\DiskSpace;

use Exception;
use OC\App\AppManager;
use OC_App;
use OC_Helper;
use OCA\Dashboard\Exceptions\WidgetIsNotCompatibleException;
use OCA\Dashboard\Exceptions\WidgetIsNotUniqueException;
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetFrame;
use OCA\Dashboard\Service\ConfigService;
use OCA\Dashboard\Service\MiscService;
use OCP\AppFramework\QueryException;
use OCP\Files\NotFoundException;
use OCP\PreConditionNotMetException;

class DiskSpaceService {

	/** @var ConfigService */
	private $configService;

	/** @var MiscService */
	private $miscService;


	/**
	 * ProviderService constructor.
	 *
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 *
	 */
	public function __construct(ConfigService $configService, MiscService $miscService) {
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * @throws NotFoundException
	 */
	public function getDiskSpace() {
		$storageInfo = OC_Helper::getStorageInfo('/');

		$diskSpace = [
			'used'  => $storageInfo['used'],
			'total' => $storageInfo['total']
		];

		return $diskSpace;
	}


}