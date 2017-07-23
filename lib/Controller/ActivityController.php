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

use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

/**
 * Description of ActivityController
 */
class ActivityController extends Controller {

	/** @var Data */
	private $data;

	/** @var DashboardService */
	private $dashboardService;

	/** @var GroupHelper */
	private $myGroupHelper;

	/** @var UserSettings */
	private $userSettings;

	/**@var string */
	private $userId;

	/**
	 * ActivityController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param DashboardService $dashboardService
	 * @param Data $data
	 * @param $userId
	 * @param GroupHelper $myGroupHelper
	 * @param UserSettings $userSettings
	 */
	public function __construct(
		$appName, IRequest $request, DashboardService $dashboardService, Data $data, $userId,
		GroupHelper $myGroupHelper, UserSettings $userSettings
	) {
		parent::__construct($appName, $request);
		$this->dashboardService = $dashboardService;
		$this->data = $data;
		$this->userId = $userId;
		$this->myGroupHelper = $myGroupHelper;
		$this->userSettings = $userSettings;
	}

	/**
	 * load Activities
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		// todo: dynamic count
		$count = 20;
		$data = $this->data->get(
			$this->myGroupHelper, $this->userSettings, $this->userId, 0, $count, "desc", "all"
		);

		$newData = [];
		$data = $data["data"];
		$loop = 0;

		$files_deleted = [];
		// Write deleted-files in a list - filter these files
		foreach ($data as $dataEvent) {
			if ($dataEvent["type"] == "file_deleted") {
				array_push($files_deleted, $dataEvent["object_name"]);
			}
		}

		// todo: Admin can configure File-Limit
		if ($loop < 6) {
			foreach ($data as $dataEvent) {
				// Write files!=created in a list
				if ($dataEvent["type"] == "file_created") {
					if ($loop < 6) {
						if (in_array($dataEvent["object_name"], $files_deleted) == false) {
							$dataActivity = array(
								[
									"object_name" => $dataEvent["object_name"],
									"link"        => $dataEvent["link"],
									"type"        => $dataEvent["type"],
									"timestamp"   => $dataEvent["timestamp"],
									"user"        => $dataEvent["user"],
								]
							);
							$newData = array_merge($newData, $dataActivity);
							$loop++;
						}
					}
				}
			}
		}

		return new DataResponse(['data' => $newData]);
	}
}