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


namespace OCA\Dashboard\Db;


use OCA\Dashboard\Model\WidgetConfig;
use OCP\DB\QueryBuilder\IQueryBuilder;

class SettingsRequestBuilder extends CoreRequestBuilder {


	/**
	 * Base of the Sql Insert request
	 *
	 * @return IQueryBuilder
	 */
	protected function getSettingsInsertSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->insert(self::TABLE_SETTINGS);

		return $qb;
	}


	/**
	 * Base of the Sql Update request
	 *
	 * @return IQueryBuilder
	 */
	protected function getSettingsUpdateSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->update(self::TABLE_SETTINGS);

		return $qb;
	}


	/**
	 * Base of the Sql Select request for Shares
	 *
	 * @return IQueryBuilder
	 */
	protected function getSettingsSelectSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();

		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$qb->select('s.widget_id', 's.user_id', 's.position', 's.settings', 's.enabled')
		   ->from(self::TABLE_SETTINGS, 's');

		$this->defaultSelectAlias = 's';

		return $qb;
	}


	/**
	 * Base of the Sql Delete request
	 *
	 * @return IQueryBuilder
	 */
	protected function getSettingsDeleteSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->delete(self::TABLE_SETTINGS);

		return $qb;
	}


	/**
	 * @param array $data
	 *
	 * @return WidgetConfig
	 */
	protected function parseSettingsSelectSql($data): WidgetConfig {
		$settings = new WidgetConfig($data['widget_id'], $data['user_id']);
		$settings->setPosition(json_decode($data['position'], true))
				 ->setSettings(json_decode($data['settings'], true))
				 ->setEnabled(($data['enabled'] === '1') ? true : false);

		return $settings;
	}

}
