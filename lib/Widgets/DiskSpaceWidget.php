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
use OCA\Dashboard\Service\Widgets\DiskSpace\DiskSpaceService;
use OCP\AppFramework\QueryException;
use OCP\Files\NotFoundException;

class DiskSpaceWidget implements IDashboardWidget {

	const WIDGET_ID = 'diskspace';


	/** @var DiskSpaceService */
	private $diskSpaceService;

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
		return 'Disk space';
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return 'Display the current use of your available disk space';
	}


	/**
	 * @return array
	 */
	public function getTemplate(): array {
		return [
			'app'      => Application::APP_NAME,
			'icon'     => 'icon-disk-space',
			'css'      => 'widgets/diskspace',
			'js'       => 'widgets/diskspace',
			'content'  => 'widgets/diskspace',
			'function' => 'OCA.DashBoard.diskspace.init',
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
					'width'  => 2,
					'height' => 1
				],
				'max'     => [
					'width'  => 3,
					'height' => 1
				]
			],
			'jobs' => [
				[
					'delay'    => 600,
					'function' => 'OCA.DashBoard.diskspace.getDiskSpace'
				]
			]
		];
	}


	/**
	 * @param IWidgetSettings $settings
	 */
	public function loadWidget(IWidgetSettings $settings) {
		$app = new Application();

		$container = $app->getContainer();
		try {
			$this->diskSpaceService = $container->query(DiskSpaceService::class);
		} catch (QueryException $e) {
			return;
		}
	}


	/**
	 * @param IWidgetRequest $request
	 *
	 * @throws NotFoundException
	 */
	public function requestWidget(IWidgetRequest $request) {
		if ($request->getRequest() === 'getDiskSpace') {
			$request->addResult('diskSpace', $this->diskSpaceService->getDiskSpace());
		}
	}


}