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
	public function navigate() {
		$widgetFrames = $this->widgetsService->getWidgetFrames(false);

		$this->feelAndLook();

		foreach ($widgetFrames as $frame) {
			$tmpl = $frame->getWidget()
						  ->getTemplate();
			if (!array_key_exists('app', $tmpl)) {
				continue;
			}

			if (array_key_exists('css', $tmpl)) {
				Util::addStyle($tmpl['app'], $tmpl['css']);
			}

			if (array_key_exists('js', $tmpl)) {
				Util::addScript($tmpl['app'], $tmpl['js']);
			}
		}

		return new TemplateResponse(Application::APP_NAME, 'navigate', []);
	}


	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @return WidgetFrame[]
	 */
	public function getWidgets() {
		$widgetFrames = $this->widgetsService->getWidgetFrames(false);

		return $widgetFrames;
	}

	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @param string $widgetId
	 */
	public function deleteWidget($widgetId) {
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
	public function saveGrid($grid) {
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