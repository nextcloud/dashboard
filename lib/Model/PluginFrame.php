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


use OCA\Dashboard\IDashboardPlugin;

class PluginFrame implements \JsonSerializable {


	/** @var IDashboardPlugin */
	private $plugin;

	/** @var array */
	private $config = [];

	/** @var array */
	private $position = [];


	/**
	 * PluginFrame constructor.
	 *
	 * @param IDashboardPlugin $plugin
	 * @param array $config
	 * @param array $position
	 */
	public function __construct(IDashboardPlugin $plugin, $config, $position) {
		$this->plugin = $plugin;
		$this->config = $config;
		$this->position = $position;
	}


	/**
	 * @return IDashboardPlugin
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * @param IDashboardPlugin $plugin
	 *
	 * @return $this
	 */
	public function setPlugin($plugin) {
		$this->plugin = $plugin;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param array $config
	 *
	 * @return $this
	 */
	public function setConfig($config) {
		$this->config = $config;

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
	 * Specify data which should be serialized to JSON
	 *
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$plugin = $this->getPlugin();

		return [
			'plugin'   => [
				'id'   => $plugin->getId(),
				'name' => $plugin->getName()
			],
			'template' => $plugin->getTemplate(),
			'setup'    => $plugin->pluginSetup(),
			'config'   => $this->getConfig(),
			'position' => $this->getPosition()
		];
	}
}