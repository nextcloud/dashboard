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
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class ActivityService {

	/** @var Data */
	private $data;

	/** @var GroupHelper */
	private $myGroupHelper;

	/** @var UserSettings */
	private $userSettings;

	/**@var string */
	private $userId;

	/** @var IDBConnection */
	protected $connection;

	/**
	 * ActivityService constructor.
	 *
	 * @param Data $data
	 * @param string $userId
	 * @param GroupHelper $myGroupHelper
	 * @param UserSettings $userSettings
	 * @param IDBConnection $connection
	 */
	function __construct(
		Data $data,
		$userId,
		GroupHelper $myGroupHelper,
		UserSettings $userSettings,
		IDBConnection $connection
	) {
		$this->data = $data;
		$this->userId = $userId;
		$this->myGroupHelper = $myGroupHelper;
		$this->userSettings = $userSettings;
		$this->connection = $connection;
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

	/**
	 * get all shared and unshared events for current user
	 * 
	 * @return array
	 */
	public function getSharedFilesFromActivity() {
		$queryBuilder = $this->connection->getQueryBuilder();
		// SELECT * FROM [prefix]_activity
		$queryBuilder->select('*')->from('activity');
		// WHERE affecteduser = [current user]
		$queryBuilder->where($queryBuilder->expr()
			->eq(
				'affecteduser',
				$queryBuilder->createNamedParameter($this->userId)
			)
		);
		// AND user != [current user]
		$queryBuilder->andWhere($queryBuilder->expr()
			->neq(
				'user',
				$queryBuilder->createNamedParameter($this->userId)
			)
		);
		// AND (subject = 'shared_with_by' OR subject = 'unshared_by')
		$queryBuilder->andWhere($queryBuilder->expr()
			->in(
				'subject',
				$queryBuilder->createNamedParameter(
					['unshared_by', 'shared_with_by',],
					IQueryBuilder::PARAM_STR_ARRAY)
			)
		);
		// ORDER BY timestamp DESC
		$queryBuilder->orderBy('timestamp', 'DESC');

		$result = $queryBuilder->execute();
		$sharedFiles = array();
		while ($row = $result->fetch()) {
			$sharedFiles[] = array(
				'object_name' => substr($row['file'], 1),
				'link'        => str_replace(
					'apps/files/?dir=/',
					'f/' . $row['object_id'],
					$row['link']
				),
				'type'        => $row['subject'],
				'timestamp'   => $row['timestamp'],
				'user'        => $row['user'],
				'file_id'     => $row['object_id']
			);
		}
		$result->closeCursor();

		return $this->filterSharedFilesResult($sharedFiles);
 	}

	/**
	 * Note: chronologic descending sorted array required
	 *
	 * @param array $sharedFiles
	 * @param int $limitResult
	 * 
	 * @return array
	 */
	private function filterSharedFilesResult ($sharedFiles, $limitResult = 6){
		$filteredSharedFiles = array();
		$ignoreFileId = array();
		$i = 0;

		foreach ($sharedFiles as $sharedFile) {
			if ($sharedFile['type'] === 'shared_with_by') {
				if (!in_array($sharedFile['file_id'], $ignoreFileId)) {
					// latest chronologic occurrence of shared
					// we add this entry to result-array and
					// ignore further entries with this file_id
					$filteredSharedFiles[] = $sharedFile;
					$ignoreFileId[] = $sharedFile['file_id'];
					$i++;
					if ($i === $limitResult) {
						break;
					}
				}
			} elseif ($sharedFile['type'] === 'unshared_by') {
				if (!in_array($sharedFile['file_id'], $ignoreFileId)) {
					// latest chronologic occurrence of unshared
					// access to this file was revoked for current user
					// we ignore further entries with this file_id
					$ignoreFileId[] = $sharedFile['file_id'];
				}
			}
		}
		return $filteredSharedFiles;
	}

}
