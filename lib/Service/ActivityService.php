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

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class ActivityService {

	/**@var string */
	private $userId;

	/** @var IDBConnection */
	protected $connection;

	/**
	 * ActivityService constructor.
	 *
	 * @param string $userId
	 * @param IDBConnection $connection
	 */
	function __construct($userId, IDBConnection $connection) {
		$this->userId = $userId;
		$this->connection = $connection;
	}

	/**
	 * get all file activities for current user
	 * and optional all shared files activities for current user
	 *
	 * @param boolean $getSharedFiles
	 * @return array
	 */
	public function getFilesFromActivity($getSharedFiles = FALSE) {
		$queryBuilder = $this->connection->getQueryBuilder();
		// SELECT * FROM [prefix]_activity
		$queryBuilder->select('*')->from('activity');
		// WHERE
		$queryBuilder->where(
		// (user = [current user] AND app = 'files')
			$queryBuilder->expr()->andX(
				$queryBuilder->expr()->eq(
					'user',
					$queryBuilder->createNamedParameter($this->userId)
				),
				$queryBuilder->expr()->eq(
					'app',
					$queryBuilder->createNamedParameter('files')
				)
			)
		);
		if ($getSharedFiles === TRUE) {
		// OR
			$queryBuilder->orWhere(
		// (affecteduser = [current user] AND (subject = 'unshared_by' OR subject = 'shared_with_by'))
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq(
						'affecteduser',
						$queryBuilder->createNamedParameter($this->userId)
					),
					$queryBuilder->expr()->in(
						'subject',
						$queryBuilder->createNamedParameter(
							['unshared_by', 'shared_with_by',],
							IQueryBuilder::PARAM_STR_ARRAY
						)
					)
				)
			);
		}
		// ORDER BY timestamp DESC
		$queryBuilder->orderBy('timestamp', 'DESC');

		$queryBuilder->setMaxResults(30);
		$result = $queryBuilder->execute();

		$files = array();
		while ($row = $result->fetch()) {
			$files[] = array(
				'object_name' => substr($row['file'], 1),
				'link'	      =>
					str_replace(
						'apps/files/?dir=/',
						'f/' . $row['object_id'],
						$row['link']
					),
				'type'	      => $row['subject'],
				'timestamp'   => $row['timestamp'],
				'user'        => $row['user'],
				'file_id'     => $row['object_id']
			);
		}
		$result->closeCursor();

		return $this->filterFilesResult($files, 6);
	}

	/**
	 * 
	 * Note: chronologic descending sorted array required
	 * 
	 * @param array $files
	 * @param int $limitResult
	 * @return array
	 */
	private function filterFilesResult($files, $limitResult = 6) {
		$filter = array(
			'created_self',
			'renamed_self',
			'restored_self',
			'shared_with_by'
		);
		$filteredFiles = array();
		$ignoreFileId = array();
		$i = 0;

		foreach ($files as $file) {
			if (in_array($file['type'], $filter)) {
				if (!in_array($file['file_id'], $ignoreFileId)) {
					$filteredFiles[] = $file;
					$ignoreFileId[] = $file['file_id'];
					$i++;
					if ($i === $limitResult) {
						break;
					}
				}
			} elseif ($file['type'] === 'unshared_by'
				|| $file['type'] === 'deleted_self')
			{
				if (!in_array($file['file_id'], $ignoreFileId)) {
					$ignoreFileId[] = $file['file_id'];
				}
			}
		}
		return $filteredFiles;
	}

}
