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


use OC\Dashboard\Model\WidgetSetting;
use OC\Dashboard\Model\WidgetSetup;
use OC\Dashboard\Model\WidgetTemplate;
use OCA\Dashboard\Model\WidgetConfig;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetConfig;
use OCP\Dashboard\Model\IWidgetSetting;
use OCP\Dashboard\Model\IWidgetSetup;
use OCP\Dashboard\Model\IWidgetTemplate;

class Test1Widget implements IDashboardWidget {

	const WIDGET_ID = 'test1';


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
		return 'Lorem Ipsum';
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor '
			   . ' incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam';
	}


	/**
	 * @return IWidgetTemplate
	 */
	public function getWidgetTemplate(): IWidgetTemplate {
		$template = new WidgetTemplate();
		$template->addCss('widgets/test1widget')
				 ->setIcon('icon-lorem')
				 ->setContent('widgets/Test1');

		$setting = new WidgetSetting(IWidgetSetting::TYPE_INPUT);
		$setting->setName('test_config');
		$setting->setTitle('Test Config');
		$setting->setPlaceholder('test');
		$template->addSetting($setting);

		return $template;
	}


	/**
	 * @return IWidgetSetup
	 */
	public function getWidgetSetup(): IWidgetSetup {
		$setup = new WidgetSetup();
		$setup->addSize(IWidgetSetup::SIZE_TYPE_MIN, 4, 3)
			  ->addSize(IWidgetSetup::SIZE_TYPE_MAX, 10, 6)
			  ->addSize(IWidgetSetup::SIZE_TYPE_DEFAULT, 6, 4);

		return $setup;
	}


	/**
	 * @param IWidgetConfig $settings
	 */
	public function loadWidget(IWidgetConfig $settings) {
	}


	/**
	 * @param IWidgetRequest $request
	 */
	public function requestWidget(IWidgetRequest $request) {
	}

}
