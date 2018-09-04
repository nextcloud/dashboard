<?php
/*
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author tuxedo-rb
 * @copyright TUXEDO Computers GmbH
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

namespace OCA\Dashboard\Migration;


use OCP\Migration\IRepairStep;
use OCP\Migration\IOutput;
use OCP\IDBConnection;
use OCP\IConfig;

/**
 * removes all dashboard tables during app-uninstall
 * 
 * see also ./dashboard/appinfo/info.xml
 */
class DropDashboardTables implements IRepairStep {

	/** @var IDBConnection */
	private $db;

	/** @var IConfig */
	private $config;

	/**
	 * @param IDBConnection $db
	 * @param IConfig $config
	 */
	public function __construct(
		IDBConnection $db,
		IConfig $config
	) {
		$this->db = $db;
		$this->config = $config;
	}

	public function getName() {
		return 'remove all Dashboard tables';
	}

	/**
	 * @param IOutput $output
	 */
	public function run(IOutput $output) {
		$this->db->dropTable('dashboard_announcements');
		$this->db->dropTable('dashboard_files');
		if ($this->db->tableExists('dashboard_files') === TRUE) {
			$this->db->dropTable('dashboard_files');
		}
		$this->db->dropTable('dashboard_settings');

		$this->config->deleteAppFromAllUsers('dashboard');
		$this->config->deleteAppValues('dashboard');

		$output->info("Dashboard tables removed");
	}

}
