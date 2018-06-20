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
use OCA\Dashboard\Exceptions\WidgetIsNotCompatibleException;
use OCA\Dashboard\Exceptions\WidgetIsNotUniqueException;
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetFrame;
use OCP\AppFramework\QueryException;
use OCP\Util;

class WidgetsService {

	/** @var AppManager */
	private $appManager;

	/** @var ConfigService */
	private $configService;

	/** @var MiscService */
	private $miscService;

	/** @var WidgetFrame[] */
	private $widgetFrames = [];

	/** @var bool */
	private $widgetsLoaded = false;


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
	 * @param bool $loadWidgets
	 *
	 * @return WidgetFrame[]
	 */
	public function getWidgetFrames($loadWidgets = false) {
		$this->getWidgets();
		if ($loadWidgets) {
			$this->loadWidgets();
		}

		return $this->widgetFrames;
	}


	/**
	 * @param string $widgetId
	 */
	public function removeWidget($widgetId) {
		if ($widgetId === '' || is_null($widgetId)) {
			return;
		}

		$this->configService->deleteAppValue('_' . $widgetId . '_pos');
	}

	/**
	 * @param array $grid
	 */
	public function saveGrid($grid) {

		foreach ($grid as $item) {
			$pos = [
				'x'      => $item['x'],
				'y'      => $item['y'],
				'width'  => $item['width'],
				'height' => $item['height']
			];

			$this->configService->setAppValue('_' . $item['widgetId'] . '_pos', json_encode($pos));
		}
	}


	/**
	 */
	private function loadWidgets() {
		if ($this->widgetsLoaded) {
			return;
		}

		foreach ($this->widgetFrames as $widgetFrame) {
			try {
				$widget = $widgetFrame->getWidget();
				$widget->loadWidget($widgetFrame->getConfig());
			} catch (Exception $e) {
				$this->miscService->log($e->getMessage());
			}
		}

		$this->widgetsLoaded = true;
	}


	/**
	 * @param IDashboardWidget $widget
	 *
	 * @return WidgetFrame
	 */
	private function generateWidgetFrame(IDashboardWidget $widget) {
		$settings = MiscService::get($widget->widgetSetup(), 'settings', []);

		$position =
			json_decode($this->configService->getAppValue('_' . $widget->getId() . '_pos', '[]'));
		$config =
			json_decode($this->configService->getAppValue('_' . $widget->getId() . '_conf', '[]'));

		foreach ($settings as $item) {
			if (!array_key_exists($item['name'], $config)) {
				$config[$item['name']] = MiscService::get($item, 'default', '');
			}
		}

		return new WidgetFrame($widget, $config, $position);
	}


	/**
	 * Load all IDashboardWidgets set in any info.xml file
	 */
	private function getWidgets() {
		if (sizeof($this->widgetFrames) > 0) {
			return;
		}

		try {
			$apps = $this->appManager->getInstalledApps();
			foreach ($apps as $appId) {
				$this->getWidgetsFromApp($appId);
			}
		} catch (Exception $e) {
			$this->miscService->log($e->getMessage());
		}
	}


	/**
	 * @param string $appId
	 *
	 * @throws WidgetIsNotCompatibleException
	 * @throws QueryException
	 */
	private function getWidgetsFromApp($appId) {
		$appInfo = OC_App::getAppInfo($appId);
		if (!is_array($appInfo) || !key_exists('dashboard', $appInfo)
			|| !key_exists('widget', $appInfo['dashboard'])) {
			return;
		}

		$widgets = $appInfo['dashboard']['widget'];
		$this->getWidgetsFromList($widgets);
	}


	/**
	 * @param string|array $widgets
	 *
	 * @throws WidgetIsNotCompatibleException
	 * @throws QueryException
	 */
	private function getWidgetsFromList($widgets) {
		if (!is_array($widgets)) {
			$widgets = [$widgets];
		}

		foreach ($widgets AS $widgetId) {
			$widget = \OC::$server->query((string)$widgetId);
			if (!($widget instanceof IDashboardWidget)) {
				throw new WidgetIsNotCompatibleException(
					$widgetId . ' is not a compatible DashboardWidget'
				);
			}

			$this->addWidgetFrame($widget);
		}
	}


	/**
	 * @param IDashboardWidget $widget
	 */
	private function addWidgetFrame(IDashboardWidget $widget) {
		try {
			$this->widgetIdMustBeUnique($widget);
			$widgetFrame = $this->generateWidgetFrame($widget);
			$this->widgetFrames[] = $widgetFrame;
		} catch (Exception $e) {
		}
	}


	/**
	 * @param IDashboardWidget $widget
	 *
	 * @throws WidgetIsNotUniqueException
	 */
	private function widgetIdMustBeUnique(IDashboardWidget $widget) {
		foreach ($this->widgetFrames as $widgetFrame) {
			$knownWidget = $widgetFrame->getWidget();
			if ($knownWidget->getId() === $widget->getId()) {
				throw new WidgetIsNotUniqueException(
					'DashboardWidget ' . $widget->getId() . ' already exist'
				);
			}
		}
	}

}