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

namespace OCA\Dashboard\Widgets;


use OCA\Dashboard\AppInfo\Application;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetSettings;
use OCA\Dashboard\Service\Widgets\Fortunes\FortunesService;
use OCP\AppFramework\QueryException;


class FortunesWidget implements IDashboardWidget {

	const WIDGET_ID = 'fortunes';


	/** @var FortunesService */
	private $fortunesService;


	/**
	 * @return string
	 */
	public function getId(): string {
		return self::WIDGET_ID;
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return 'Fortune Quotes';
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return 'Get a random fortune quote';
	}


	/**
	 * @return array
	 */
	public function getTemplate(): array {
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
	public function widgetSetup(): array {
		return [
			'size' => [
				'min'     => [
					'width'  => 2,
					'height' => 1
				],
				'default' => [
					'width'  => 3,
					'height' => 2
				],
				'max'     => [
					'width'  => 4,
					'height' => 4
				]
			],
			'menu' => [
				[
					'icon'     => 'icon-fortunes',
					'text'     => 'New fortune',
					'function' => 'OCA.DashBoard.fortunes.getFortune'
				]
			],
			'jobs' => [
				[
					'delay'    => 300,
					'function' => 'OCA.DashBoard.fortunes.getFortune'
				]
			],
			'push' => 'OCA.DashBoard.fortunes.push'
		];
	}


	/**
	 * @param IWidgetSettings $settings
	 */
	public function loadWidget(IWidgetSettings $settings) {
		$app = new Application();

		$container = $app->getContainer();
		try {
			$this->fortunesService = $container->query(FortunesService::class);
		} catch (QueryException $e) {
			return;
		}
	}


	/**
	 * @param IWidgetRequest $request
	 */
	public function requestWidget(IWidgetRequest $request) {
		if ($request->getRequest() === 'getFortune') {
			$request->addResult('fortune', $this->fortunesService->getRandomFortune());
		}
	}


}