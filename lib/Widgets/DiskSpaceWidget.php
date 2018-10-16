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


use OCP\Dashboard\Model\WidgetSetup;
use OCP\Dashboard\Model\WidgetTemplate;
use OCA\Dashboard\Service\Widgets\DiskSpace\DiskSpaceService;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetConfig;
use OCP\Files\NotFoundException;
use OCP\IL10N;

class DiskSpaceWidget implements IDashboardWidget {

	const WIDGET_ID = 'diskspace';


	/** @var IL10N */
	private $l10n;


	/** @var DiskSpaceService */
	private $diskSpaceService;


	public function __construct(IL10N $l10n, DiskSpaceService $diskSpaceService) {
		$this->l10n = $l10n;
		$this->diskSpaceService = $diskSpaceService;
	}


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
		return $this->l10n->t('Disk space');
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->l10n->t('Display the current use of your available disk space');
	}


	/**
	 * @return WidgetTemplate
	 */
	public function getWidgetTemplate(): WidgetTemplate {
		$template = new WidgetTemplate();
		$template->addCss('widgets/diskspace')
				 ->addJs('widgets/diskspace')
				 ->setIcon('icon-disk-space')
				 ->setContent('widgets/diskspace')
				 ->setInitFunction('OCA.DashBoard.diskspace.init');

		return $template;
	}


	/**
	 * @return WidgetSetup
	 */
	public function getWidgetSetup(): WidgetSetup {
		$setup = new WidgetSetup();
		$setup->addSize(WidgetSetup::SIZE_TYPE_MIN, 2, 1)
			  ->addSize(WidgetSetup::SIZE_TYPE_MAX, 3, 1)
			  ->addSize(WidgetSetup::SIZE_TYPE_DEFAULT, 2, 1);

		$setup->addDelayedJob('OCA.DashBoard.diskspace.getDiskSpace', 600);

		return $setup;
	}


	/**
	 * @param IWidgetConfig $settings
	 */
	public function loadWidget(IWidgetConfig $settings) {
//		$app = new Application();

//		$container = $app->getContainer();
//		try {
//			$this->diskSpaceService = $container->query(DiskSpaceService::class);
//		} catch (QueryException $e) {
//			return;
//		}
	}


	/**
	 * @param IWidgetRequest $request
	 *
	 * @throws NotFoundException
	 */
	public function requestWidget(IWidgetRequest $request) {
		if ($request->getRequest() === 'getDiskSpace') {
			$request->addResultArray('diskSpace', $this->diskSpaceService->getDiskSpace());
		}
	}


}
