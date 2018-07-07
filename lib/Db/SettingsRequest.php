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

namespace OCA\Dashboard\Db;


use OCA\Dashboard\Model\WidgetSettings;

class SettingsRequest extends SettingsRequestBuilder {


	/**
	 * @param WidgetSettings $settings
	 */
	public function create(WidgetSettings $settings) {
		$qb = $this->getSettingsInsertSql();
		$qb->setValue('user_id', $qb->createNamedParameter($settings->getUserId()))
		   ->setValue('widget_id', $qb->createNamedParameter($settings->getWidgetId()))
		   ->setValue('position', $qb->createNamedParameter(json_encode($settings->getPosition())))
		   ->setValue('settings', $qb->createNamedParameter(json_encode($settings->getSettings())))
		   ->setValue('enabled', $qb->createNamedParameter($settings->isEnabled()));

		$qb->execute();
	}


	/**
	 * @param $widgetId
	 * @param $userId
	 *
	 * @return WidgetSettings
	 */
	public function get($widgetId, $userId) {

		$qb = $this->getSettingsSelectSql();
		$this->limitToWidgetId($qb, $widgetId);
		$this->limitToUserId($qb, $userId);

		$cursor = $qb->execute();
		$data = $cursor->fetch();
		if ($data === false) {
			return new WidgetSettings($widgetId, $userId);
		}

		$cursor->closeCursor();

		return $this->parseSettingsSelectSql($data);
	}


	/**
	 * @param WidgetSettings $settings
	 */
	public function savePosition(WidgetSettings $settings) {
		$qb = $this->getSettingsUpdateSql();
		$qb->set('position', $qb->createNamedParameter(json_encode($settings->getPosition())))
		   ->set('enabled', $qb->createNamedParameter($settings->isEnabled()));

		$this->limitToWidgetId($qb, $settings->getWidgetId());
		$this->limitToUserId($qb, $settings->getUserId());

		$cursor = $qb->execute();
		if ($cursor === 0) {
			$this->create($settings);
		}
	}


	/**
	 * @param string $widgetId
	 * @param string $userId
	 */
	public function disableWidget($widgetId, $userId) {
		$qb = $this->getSettingsUpdateSql();
		$qb->set('enabled', $qb->createNamedParameter(false));

		$this->limitToWidgetId($qb, $widgetId);
		$this->limitToUserId($qb, $userId);

		$qb->execute();
	}

}
