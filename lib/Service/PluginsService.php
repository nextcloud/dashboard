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

namespace OCA\Dashboard\Service;

use Exception;
use OC\App\AppManager;
use OC_App;
use OCA\Dashboard\Exceptions\PluginIsNotCompatibleException;
use OCA\Dashboard\Exceptions\PluginIsNotUniqueException;
use OCA\Dashboard\IDashboardPlugin;
use OCA\Dashboard\Model\PluginFrame;
use OCP\AppFramework\QueryException;
use OCP\Util;

class PluginsService {

	/** @var AppManager */
	private $appManager;

	/** @var ConfigService */
	private $configService;

	/** @var MiscService */
	private $miscService;

	/** @var PluginFrame[] */
	private $pluginFrames = [];

	/** @var bool */
	private $pluginsLoaded = false;


	/**
	 * ProviderService constructor.
	 *
	 * @param AppManager $appManager
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 *
	 */
	public function __construct(
		AppManager $appManager, ConfigService $configService, MiscService $miscService
	) {
		$this->appManager = $appManager;
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * @param bool $loadPlugins
	 *
	 * @return PluginFrame[]
	 */
	public function getPluginFrames($loadPlugins = false) {
		$this->getPlugins();
		if ($loadPlugins) {
			$this->loadPlugins();
		}

		return $this->pluginFrames;
	}


	/**
	 */
	private function loadPlugins() {
		if ($this->pluginsLoaded) {
			return;
		}

		foreach ($this->pluginFrames as $pluginFrame) {
			try {
				$plugin = $pluginFrame->getPlugin();
				$plugin->loadPlugin($pluginFrame->getConfig());
			} catch (Exception $e) {
				$this->miscService->log($e->getMessage());
			}
		}

		$this->pluginsLoaded = true;
	}


	/**
	 * @param IDashboardPlugin $plugin
	 *
	 * @return PluginFrame
	 */
	private function generatePluginFrame(IDashboardPlugin $plugin) {
		$settings = MiscService::get($plugin->pluginSetup(), 'settings', []);

		$position =
			json_decode($this->configService->getAppValue('_' . $plugin->getId() . '_pos', '[]'));
		$config =
			json_decode($this->configService->getAppValue('_' . $plugin->getId() . '_conf', '[]'));

		foreach ($settings as $item) {
			if (!array_key_exists($item['name'], $config)) {
				$config[$item['name']] = MiscService::get($item, 'default', '');
			}
		}

		return new PluginFrame($plugin, $config, $position);
	}


	/**
	 * Load all IDashboardPlugins set in any info.xml file
	 */
	private function getPlugins() {
		if (sizeof($this->pluginFrames) > 0) {
			return;
		}

		try {
			$apps = $this->appManager->getInstalledApps();
			foreach ($apps as $appId) {
				$this->getPluginsFromApp($appId);
			}
		} catch (Exception $e) {
			$this->miscService->log($e->getMessage());
		}
	}


	/**
	 * @param string $appId
	 *
	 * @throws PluginIsNotCompatibleException
	 * @throws QueryException
	 */
	private function getPluginsFromApp($appId) {
		$appInfo = OC_App::getAppInfo($appId);
		if (!is_array($appInfo) || !key_exists('dashboard', $appInfo)
			|| !key_exists('plugin', $appInfo['dashboard'])) {
			return;
		}

		$plugins = $appInfo['dashboard']['plugin'];
		$this->getPluginsFromList($plugins);
	}


	/**
	 * @param string|array $plugins
	 *
	 * @throws PluginIsNotCompatibleException
	 * @throws QueryException
	 */
	private function getPluginsFromList($plugins) {
		if (!is_array($plugins)) {
			$plugins = [$plugins];
		}

		foreach ($plugins AS $pluginId) {
			$plugin = \OC::$server->query((string)$pluginId);
			if (!($plugin instanceof IDashboardPlugin)) {
				throw new PluginIsNotCompatibleException(
					$pluginId . ' is not a compatible PluginIsNotCompatibleException'
				);
			}

			$this->addPluginFrame($plugin);
		}
	}


	/**
	 * @param IDashboardPlugin $plugin
	 */
	private function addPluginFrame(IDashboardPlugin $plugin) {
		try {
			$this->pluginIdMustBeUnique($plugin);
			$pluginFrame = $this->generatePluginFrame($plugin);
			$this->pluginFrames[] = $pluginFrame;
		} catch (Exception $e) {
		}
	}


	/**
	 * @param IDashboardPlugin $plugin
	 *
	 * @throws PluginIsNotUniqueException
	 */
	private function pluginIdMustBeUnique(IDashboardPlugin $plugin) {
		foreach ($this->pluginFrames as $pluginFrame) {
			$knownPlugin = $pluginFrame->getPlugin();
			if ($knownPlugin->getId() === $plugin->getId()) {
				throw new PluginIsNotUniqueException(
					'DashboardPlugin ' . $plugin->getId() . ' already exist'
				);
			}
		}
	}

}