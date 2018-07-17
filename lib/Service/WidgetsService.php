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

namespace OCA\Dashboard\Service;

use Exception;
use OC\App\AppManager;
use OC_App;
use OCA\Dashboard\Db\SettingsRequest;
use OCA\Dashboard\Exceptions\WidgetDoesNotExistException;
use OCA\Dashboard\Exceptions\WidgetIsNotCompatibleException;
use OCA\Dashboard\Exceptions\WidgetIsNotUniqueException;
use OCA\Dashboard\Model\WidgetFrame;
use OCA\Dashboard\Model\WidgetSettings;
use OCP\AppFramework\QueryException;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetSettings;

class WidgetsService {

	/** @var string */
	private $userId;

	/** @var AppManager */
	private $appManager;

	/** @var SettingsRequest */
	private $settingsRequest;

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
	 * @param string $userId
	 * @param AppManager $appManager
	 * @param SettingsRequest $settingsRequest
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 */
	public function __construct(
		$userId, AppManager $appManager, SettingsRequest $settingsRequest,
		ConfigService $configService, MiscService $miscService
	) {
		$this->userId = $userId;
		$this->appManager = $appManager;
		$this->settingsRequest = $settingsRequest;
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * @param string $widgetId
	 * @param string $userId
	 *
	 * @return IWidgetSettings
	 */
	public function getWidgetSettings(string $widgetId, string $userId): IWidgetSettings {
		return $this->settingsRequest->get($widgetId, $userId);
	}


	/**
	 * @param bool $loadWidgets
	 *
	 * @return WidgetFrame[]
	 */
	public function getWidgetFrames(bool $loadWidgets = false): array {
		$this->getWidgets();
		if ($loadWidgets) {
			$this->loadWidgets();
		}

		return $this->widgetFrames;
	}


	/**
	 * @param string $widgetId
	 */
	public function removeWidget(string $widgetId) {
		if ($widgetId === '' || is_null($widgetId)) {
			return;
		}

		$this->settingsRequest->disableWidget($widgetId, $this->userId);
	}


	/**
	 * @param array $grid
	 */
	public function saveGrid(array $grid) {
		foreach ($grid as $item) {
			$pos = [
				'x'      => $item['x'],
				'y'      => $item['y'],
				'width'  => $item['width'],
				'height' => $item['height']
			];

			$settings = new WidgetSettings($item['widgetId'], $this->userId);
			$settings->setPosition($pos);
			$settings->setEnabled(true);

			$this->settingsRequest->savePosition($settings);
		}
	}


	/**
	 * @param string $widgetId
	 *
	 * @return WidgetFrame
	 * @throws WidgetDoesNotExistException
	 */
	public function getWidgetFrame(string $widgetId): WidgetFrame {
		$widgetFrames = $this->getWidgetFrames();
		foreach ($widgetFrames as $frame) {
			$widget = $frame->getWidget();
			if ($widget->getId() === $widgetId) {
				return $frame;
			}
		}

		throw new WidgetDoesNotExistException('Widget does not exist');
	}


	/**
	 * @param IWidgetRequest $widgetRequest
	 *
	 * @throws WidgetDoesNotExistException
	 */
	public function initWidgetRequest(IWidgetRequest $widgetRequest) {
		$widgetId = $widgetRequest->getWidgetId();
		$widgetFrame = $this->getWidgetFrame($widgetId);

		$widget = $widgetFrame->getWidget();
		$widget->loadWidget($widgetFrame->getSettings());

		$widgetRequest->setWidget($widget);
	}


	/**
	 * @param IWidgetRequest $widgetRequest
	 */
	public function requestWidget(IWidgetRequest $widgetRequest) {
		$widget = $widgetRequest->getWidget();
		$widget->requestWidget($widgetRequest);
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
				$widget->loadWidget($widgetFrame->getSettings());
			} catch (Exception $e) {
				$this->miscService->log($e->getMessage());
			}
		}

		$this->widgetsLoaded = true;
	}


	/**
	 * @param IDashboardWidget $widget
	 * @param string $userId
	 *
	 * @return WidgetFrame
	 */
	private function generateWidgetFrame(IDashboardWidget $widget, string $userId = ''
	): WidgetFrame {

		if ($userId === '') {
			$userId = $this->userId;
		}

		$settings = $this->settingsRequest->get($widget->getId(), $userId);
		$settings->setDefaultSettings((array) MiscService::get($widget->widgetSetup(), 'settings', []));

		return new WidgetFrame($widget, $settings);
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
	private function getWidgetsFromApp(string $appId) {
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
			/** we do nohtin */
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