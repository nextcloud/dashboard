<?php

/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 * @author Maxence Lange <maxence@nextcloud.com>
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
		$activityData = $this->activityService->getFilesFromActivity(TRUE);
		return new DataResponse(['data' => $activityData]);
	}

}
