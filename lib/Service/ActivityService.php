<?php
/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange
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

namespace OCA\Dashboard\Service;

use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;

class ActivityService {

	/** @var Data */
	private $data;

	/** @var GroupHelper */
	private $myGroupHelper;

	/** @var UserSettings */
	private $userSettings;

	/**@var string */
	private $userId;


	/**
	 * ActivityService constructor.
	 *
	 * @param Data $data
	 * @param string $userId
	 * @param GroupHelper $myGroupHelper
	 * @param UserSettings $userSettings
	 */
	function __construct(Data $data, $userId, GroupHelper $myGroupHelper, UserSettings $userSettings
	) {
		$this->data = $data;
		$this->userId = $userId;
		$this->myGroupHelper = $myGroupHelper;
		$this->userSettings = $userSettings;
	}


	/**
	 * @return array
	 */
	public function getDeletedFilesFromActivity() {
		$files = $this->getFilesFromActivity('file_deleted');
		$deleted = [];
		foreach ($files as $file) {
			$deleted[] = [
				$file['object_name'],
				$file['timestamp']
			];
		}

		return $deleted;
	}


	/**
	 * @return array
	 */
	public function getCreatedFilesFromActivity() {
		return $this->getFilesFromActivity('file_created');
	}


	/**
	 * @param string $type
	 *
	 * @return array
	 */
	private function getFilesFromActivity($type) {
		$entries = $this->data->get(
			$this->myGroupHelper, $this->userSettings, $this->userId, 0, 20, "desc",
			'all', 'files'
		)['data'];

		$files = [];
		foreach ($entries as $entry) {
			if ($entry['type'] === $type) {
				foreach ($entry['subject_rich'][1] as $file) {
					$files[] = [
						'object_name' => $file['name'],
						'link'        => $file['link'],
						'type'        => $entry['type'],
						'timestamp'   => $entry['timestamp'],
						'user'        => $entry['user']
					];
				}
			}
		}

		return $files;
	}

}
