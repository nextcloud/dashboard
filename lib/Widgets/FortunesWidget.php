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


use OC\Dashboard\Model\WidgetSetup;
use OC\Dashboard\Model\WidgetTemplate;
use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\Service\Widgets\Fortunes\FortunesService;
use OCP\AppFramework\QueryException;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetConfig;
use OCP\IL10N;


class FortunesWidget implements IDashboardWidget {

	const WIDGET_ID = 'fortunes';


	/** @var IL10N */
	private $l10n;


	/** @var FortunesService */
	private $fortunesService;


	public function __construct(IL10N $l10n) {
		$this->l10n = $l10n;
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
		return $this->l10n->t('Fortune Quotes');
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->l10n->t('Get a random fortune quote');
	}


	/**
	 * @return WidgetTemplate
	 */
	public function getWidgetTemplate(): WidgetTemplate {
		$template = new WidgetTemplate();
		$template->addCss('widgets/fortunes')
				 ->addJs('widgets/fortunes')
				 ->setIcon('icon-fortunes')
				 ->setContent('widgets/fortunes')
				 ->setInitFunction('OCA.DashBoard.fortunes.init');

		return $template;
	}


	/**
	 * @return WidgetSetup
	 */
	public function getWidgetSetup(): WidgetSetup {
		$setup = new WidgetSetup();
		$setup->addSize(WidgetSetup::SIZE_TYPE_MIN, 2, 1)
			  ->addSize(WidgetSetup::SIZE_TYPE_MAX, 4, 4)
			  ->addSize(WidgetSetup::SIZE_TYPE_DEFAULT, 3, 2);

		$setup->addMenuEntry('OCA.DashBoard.fortunes.getFortune', 'icon-fortunes', 'New Fortune');
		$setup->addDelayedJob('OCA.DashBoard.fortunes.getFortune', 300);
		$setup->setPush('OCA.DashBoard.fortunes.push');

		return $setup;
	}


	/**
	 * @param IWidgetConfig $settings
	 */
	public function loadWidget(IWidgetConfig $settings) {
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
