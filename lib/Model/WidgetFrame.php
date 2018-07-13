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

namespace OCA\Dashboard\Model;


use OCP\AppFramework\Http\TemplateResponse;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetSettings;

class WidgetFrame implements \JsonSerializable {


	/** @var IDashboardWidget */
	private $widget;

	/** @var WidgetSettings */
	private $settings;


	/**
	 * WidgetFrame constructor.
	 *
	 * @param IDashboardWidget $widget
	 * @param IWidgetSettings $settings
	 */
	public function __construct(IDashboardWidget $widget, IWidgetSettings $settings) {
		$this->widget = $widget;
		$this->settings = $settings;
	}


	/**
	 * @return IDashboardWidget
	 */
	public function getWidget(): IDashboardWidget {
		return $this->widget;
	}

	/**
	 * @param IDashboardWidget $widget
	 *
	 * @return $this
	 */
	public function setWidget(IDashboardWidget $widget): WidgetFrame {
		$this->widget = $widget;

		return $this;
	}


	/**
	 * @return IWidgetSettings
	 */
	public function getSettings(): IWidgetSettings {
		return $this->settings;
	}

	/**
	 * @param IWidgetSettings $settings
	 *
	 * @return $this
	 */
	public function setConfig(IWidgetSettings $settings): WidgetFrame {
		$this->settings = $settings;

		return $this;
	}


	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize(): array {
		$widget = $this->getWidget();

		$template = $widget->getTemplate();
		$html = new TemplateResponse($template['app'], $template['content'], [], 'blank');

		return [
			'widget'   => [
				'id'          => $widget->getId(),
				'name'        => $widget->getName(),
				'description' => $widget->getDescription()
			],
			'template' => $widget->getTemplate(),
			'setup'    => $widget->widgetSetup(),
			'html'     => $html->render(),
			'config'   => $this->getSettings()
		];
	}
}