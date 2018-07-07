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

namespace OCA\Dashboard\Model;


use OCA\Dashboard\Service\MiscService;

class WidgetSettings implements \JsonSerializable {

	/** @var string */
	private $userId;

	/** @var string */
	private $widgetId;

	/** @var array */
	private $position = [];

	/** @var array */
	private $settings = [];

	/** @var bool */
	private $enabled = false;

	/**
	 * WidgetSettings constructor.
	 *
	 * @param $userId
	 * @param $widgetId
	 */
	public function __construct($widgetId, $userId) {
		$this->widgetId = $widgetId;
		$this->userId = $userId;
	}


	/**
	 * @return string
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param string $userId
	 *
	 * @return $this
	 */
	public function setUserId($userId) {
		$this->userId = $userId;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getWidgetId() {
		return $this->widgetId;
	}

	/**
	 * @param string $widgetId
	 *
	 * @return $this
	 */
	public function setWidgetId($widgetId) {
		$this->widgetId = $widgetId;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param array $position
	 *
	 * @return $this
	 */
	public function setPosition($position) {
		$this->position = $position;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * @param array $settings
	 *
	 * @return $this
	 */
	public function setSettings($settings) {
		$this->settings = $settings;

		return $this;
	}


	/**
	 * @param array $default
	 */
	public function setDefaultSettings($default) {
		$curr = $this->getSettings();
		foreach ($default as $item) {
			if (!array_key_exists($item['name'], $curr)) {
				$curr[$item['name']] = MiscService::get($item, 'default', '');
			}
		}

		$this->setSettings($curr);
	}


	/**
	 * @return bool
	 */
	public function isEnabled() {
		return $this->enabled;
	}

	/**
	 * @param bool $enabled
	 *
	 * @return $this
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;

		return $this;
	}


	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			'widgetId' => $this->getWidgetId(),
			'userId'   => $this->getUserId(),
			'position' => $this->getPosition(),
			'settings' => $this->getSettings(),
			'enabled'  => $this->isEnabled()
		];
	}
}