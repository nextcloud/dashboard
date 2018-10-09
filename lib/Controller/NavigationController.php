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


namespace OCA\Dashboard\Controller;

use Exception;
use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\Model\WidgetFrame;
use OCA\Dashboard\Service\ConfigService;
use OCA\Dashboard\Service\MiscService;
use OCA\Dashboard\Service\WidgetsService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\Util;

class NavigationController extends Controller {


	/** @var ConfigService */
	private $configService;

	/** @var WidgetsService */
	private $widgetsService;

	/** @var MiscService */
	private $miscService;


	/**
	 * NavigationController constructor.
	 *
	 * @param IRequest $request
	 * @param ConfigService $configService
	 * @param WidgetsService $widgetsService
	 * @param MiscService $miscService
	 */
	public function __construct(
		IRequest $request, ConfigService $configService, WidgetsService $widgetsService,
		MiscService $miscService
	) {
		parent::__construct(Application::APP_NAME, $request);

		$this->configService = $configService;
		$this->widgetsService = $widgetsService;
		$this->miscService = $miscService;
	}


	/**
	 * @NoCSRFRequired
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @return TemplateResponse
	 */
	public function navigate(): TemplateResponse {
		$widgetFrames = $this->widgetsService->getWidgetFrames(false);

		$this->feelAndLook();

		foreach ($widgetFrames as $frame) {
			$tmpl = $frame->getTemplate();

			$this->includeWidgetCss($frame->getAppId(), $tmpl->getCss());
			$this->includeWidgetJs($frame->getAppId(), $tmpl->getJs());
		}

		return new TemplateResponse(Application::APP_NAME, 'navigate', []);
	}


	/**
	 * @param string $appId
	 * @param array $css
	 */
	private function includeWidgetCss(string $appId, array $css) {
		foreach ($css as $file) {
			Util::addStyle($appId, $file);
		}
	}


	/**
	 * @param string $appId
	 * @param array $js
	 */
	private function includeWidgetJs(string $appId, array $js) {
		foreach ($js as $file) {
			Util::addScript($appId, $file);
		}
	}


	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @return WidgetFrame[]
	 */
	public function getWidgets(): array {
		$widgetFrames = $this->widgetsService->getWidgetFrames(false);

		return $widgetFrames;
	}

	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @param string $widgetId
	 */
	public function deleteWidget(string $widgetId) {
		$this->widgetsService->removeWidget($widgetId);
	}


	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @param string $grid
	 *
	 * @return WidgetFrame[]
	 */
	public function saveGrid(string $grid): array {
		try {
			$grid = json_decode($grid, true);
			$this->widgetsService->saveGrid($grid);

			return ['result' => 'done'];
		} catch (Exception $e) {
			return ['result' => 'fail', 'message' => $e->getMessage()];
		}
	}


	/**
	 *
	 */
	private function feelAndLook() {
		Util::addScript(Application::APP_NAME, 'gridstack.all');
		Util::addScript(Application::APP_NAME, 'jquery.flip');

		Util::addScript(Application::APP_NAME, 'dashboard.navigation');
		Util::addScript(Application::APP_NAME, 'dashboard.net');
		Util::addScript(Application::APP_NAME, 'dashboard.api');
		Util::addScript(Application::APP_NAME, 'dashboard.settings');
		Util::addScript(Application::APP_NAME, 'dashboard.grid');
		Util::addScript(Application::APP_NAME, 'dashboard');

		Util::addStyle(Application::APP_NAME, 'gridstack');
		Util::addStyle(Application::APP_NAME, 'dashboard');
	}
}
