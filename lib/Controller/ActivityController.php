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

namespace OCA\Dashboard\Controller;

use OCA\Dashboard\Service\ActivityService;
use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

/**
 * Description of ActivityController
 */
class ActivityController extends Controller {

	/** @var DashboardService */
	private $dashboardService;

	/** @var ActivityService */
	private $activityService;

	/**
	 * ActivityController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param DashboardService $dashboardService
	 * @param ActivityService $activityService
	 *
	 * @internal param $userId
	 * @internal param GroupHelper $myGroupHelper
	 * @internal param UserSettings $userSettings
	 */
	public function __construct(
		$appName, IRequest $request, DashboardService $dashboardService,
		ActivityService $activityService
	) {
		parent::__construct($appName, $request);
		$this->dashboardService = $dashboardService;
		$this->activityService = $activityService;
	}

	/**
	 * load Activities
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return DataResponse
	 */
	public function index() {

		$filesDeleted = $this->activityService->getDeletedFilesFromActivity();
		$filesCreated = $this->activityService->getCreatedFilesFromActivity();

		$newData = [];
		foreach ($filesCreated as $files) {

			if (in_array($files['object_name'], $filesDeleted)) {
				continue;
			}

			$newData[] = [
				'object_name' => $files['object_name'],
				'link'        => $files['link'],
				'type'        => $files['type'],
				'timestamp'   => $files['timestamp'],
				'user'        => $files['user']
			];

			// todo: Admin can configure File-Limit
			if (sizeof($newData) >= 6) {
				break;
			}
		}

		return new DataResponse(['data' => $newData]);
	}


}