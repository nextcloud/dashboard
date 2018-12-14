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
use OCA\Dashboard\Db\DashboardSettings;
use OCA\Dashboard\Db\DashboardSettingsMapper;
use OCP\IConfig;

/**
 * writes default settings into table dashboard_settings during app-installation
 * 
 * see also ./dashboard/appinfo/info.xml
 */
class SetDefaultDashboardSettings implements IRepairStep {

	/** @var DashboardSettings */
	private $dashboardSettings;

	/** @var DashboardSettingsMapper */
	private $dashboardSettingsMapper;

	/** @var IConfig */
	private $config;

	/**
	 * @param DashboardSettings $dashboardSettings
	 * @param DashboardSettingsMapper $dashboardSettingsMapper
	 * @param IConfig $config
	 */
	public function __construct(
		DashboardSettings $dashboardSettings,
		DashboardSettingsMapper $dashboardSettingsMapper,
		IConfig $config
	) {
		$this->dashboardSettings = $dashboardSettings;
		$this->dashboardSettingsMapper = $dashboardSettingsMapper;
		$this->config = $config;
	}

	public function getName() {
		return 'set default Dashboard settings';
	}

	/**
	 * @param IOutput $output
	 */
	public function run(IOutput $output) {
		// enabling the app is not the only one event which triggers install
		if ($this->config->getAppValue('dashboard', 'setDefaultDashboardSettings') === 'yes') {
			$output->info('default Dashboard settings already saved');
			return;
		}

		$this->dashboardSettings->setId(1);
		$this->dashboardSettings->setKey('show_activity');
		$this->dashboardSettings->setValue(1);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(2);
		$this->dashboardSettings->setKey('show_inbox');
		$this->dashboardSettings->setValue(1);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(3);
		$this->dashboardSettings->setKey('show_announcement');
		$this->dashboardSettings->setValue(1);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(4);
		$this->dashboardSettings->setKey('show_calendar');
		$this->dashboardSettings->setValue(1);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(5);
		$this->dashboardSettings->setKey('show_wide_activity');
		$this->dashboardSettings->setValue(0);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(6);
		$this->dashboardSettings->setKey('show_wide_inbox');
		$this->dashboardSettings->setValue(0);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(7);
		$this->dashboardSettings->setKey('show_wide_announcement');
		$this->dashboardSettings->setValue(0);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(8);
		$this->dashboardSettings->setKey('show_wide_calendar');
		$this->dashboardSettings->setValue(0);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(9);
		$this->dashboardSettings->setKey('activity_position');
		$this->dashboardSettings->setValue(1);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(10);
		$this->dashboardSettings->setKey('inbox_position');
		$this->dashboardSettings->setValue(2);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(11);
		$this->dashboardSettings->setKey('announcement_position');
		$this->dashboardSettings->setValue(3);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->dashboardSettings->setId(12);
		$this->dashboardSettings->setKey('calendar_position');
		$this->dashboardSettings->setValue(4);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);
		
		$this->dashboardSettings->setId(13);
		$this->dashboardSettings->setKey('show_quota');
		$this->dashboardSettings->setValue(1);
		$this->dashboardSettingsMapper->insertOrUpdate($this->dashboardSettings);

		$this->config->setAppValue('dashboard', 'setDefaultDashboardSettings', 'yes');

		$output->info("initial default Dashboard settings saved");
	}

}
