<?php
declare(strict_types=1);


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

namespace OCA\Dashboard\Model;


use OCA\Dashboard\Service\MiscService;
use OCP\Dashboard\Model\IWidgetConfig;

class WidgetConfig implements IWidgetConfig, \JsonSerializable {


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
	 * WidgetConfig constructor.
	 *
	 * @param string $widgetId
	 * @param string $userId
	 */
	public function __construct(string $widgetId, string $userId) {
		$this->widgetId = $widgetId;
		$this->userId = $userId;
	}


	/**
	 * @return string
	 */
	public function getUserId(): string {
		return $this->userId;
	}

	/**
	 * @param string $userId
	 *
	 * @return $this
	 */
	public function setUserId(string $userId): IWidgetConfig {
		$this->userId = $userId;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getWidgetId(): string {
		return $this->widgetId;
	}

	/**
	 * @param string $widgetId
	 *
	 * @return $this
	 */
	public function setWidgetId(string $widgetId): IWidgetConfig {
		$this->widgetId = $widgetId;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getPosition(): array {
		return $this->position;
	}

	/**
	 * @param array $position
	 *
	 * @return $this
	 */
	public function setPosition(array $position): IWidgetConfig {
		$this->position = $position;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getSettings(): array {
		return $this->settings;
	}

	/**
	 * @param array $settings
	 *
	 * @return $this
	 */
	public function setSettings(array $settings): IWidgetConfig {
		$this->settings = $settings;

		return $this;
	}


	/**
	 * @param array $default
	 */
	public function setDefaultSettings(array $default) {
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
	public function isEnabled(): bool {
		return $this->enabled;
	}

	/**
	 * @param bool $enabled
	 *
	 * @return $this
	 */
	public function setEnabled(bool $enabled): IWidgetConfig {
		$this->enabled = $enabled;

		return $this;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return [
			'widgetId' => $this->getWidgetId(),
			'userId'   => $this->getUserId(),
			'position' => $this->getPosition(),
			'settings' => $this->getSettings(),
			'enabled'  => $this->isEnabled()
		];
	}
}
