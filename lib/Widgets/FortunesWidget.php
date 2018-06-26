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

namespace OCA\Dashboard\Widgets;


use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetRequest;
use OCA\Dashboard\Service\Widgets\Fortunes\FortunesService;
use OCP\AppFramework\QueryException;

class FortunesWidget implements IDashboardWidget {

	const WIDGET_ID = 'fortunes';


	/** @var FortunesService */
	private $fortunesService;


	/**
	 * @return string
	 */
	public function getId() {
		return self::WIDGET_ID;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return 'Fortune Quotes';
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return 'Get a random fortune quote';
	}


	/**
	 * @return array
	 */
	public function getTemplate() {
		return [
			'app'      => Application::APP_NAME,
			'icon'     => 'icon-fortunes',
			'css'      => 'widgets/fortunes',
			'js'       => 'widgets/fortunes',
			'content'  => 'widgets/fortunes',
			'function' => 'OCA.DashBoard.fortunes.init'
		];
	}


	/**
	 * @return array
	 */
	public function widgetSetup() {
		return [
			'size' => [
				'width'  => 4,
				'height' => 2
			],
			'menu' => [
				[
					'icon'     => 'icon-fortunes',
					'text'     => 'New fortune',
					'function' => 'OCA.DashBoard.fortunes.getFortune'
				]
			]
		];
	}


	/**
	 * @param array $config
	 */
	public function loadWidget($config) {
		$app = new Application();

		$container = $app->getContainer();
		try {
			$this->fortunesService = $container->query(FortunesService::class);
		} catch (QueryException $e) {
			return;
		}
	}


	/**
	 * @param WidgetRequest $request
	 */
	public function requestWidget(WidgetRequest $request) {
		if ($request->getRequest() === 'getFortune') {
			$request->addResult('fortune', $this->fortunesService->getRandomFortune());
		}
	}


}