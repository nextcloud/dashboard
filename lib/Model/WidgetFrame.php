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

namespace OCA\Dashboard\Model;


use OCA\Dashboard\IDashboardWidget;
use OCP\AppFramework\Http\TemplateResponse;

class WidgetFrame implements \JsonSerializable {


	/** @var IDashboardWidget */
	private $widget;

	/** @var array */
	private $config = [];

	/** @var array */
	private $position = [];


	/**
	 * WidgetFrame constructor.
	 *
	 * @param IDashboardWidget $widget
	 * @param array $config
	 * @param array $position
	 */
	public function __construct(IDashboardWidget $widget, $config, $position) {
		$this->widget = $widget;
		$this->config = $config;
		$this->position = $position;
	}


	/**
	 * @return IDashboardWidget
	 */
	public function getWidget() {
		return $this->widget;
	}

	/**
	 * @param IDashboardWidget $widget
	 *
	 * @return $this
	 */
	public function setWidget($widget) {
		$this->widget = $widget;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param array $config
	 *
	 * @return $this
	 */
	public function setConfig($config) {
		$this->config = $config;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param array $position
	 *
	 * @return $this
	 */
	public function setPosition($position) {
		$this->position = $position;

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
	public function jsonSerialize() {
		$widget = $this->getWidget();

		$html = '';
		$setup = $widget->widgetSetup();
		if (array_key_exists('template', $setup)) {
			$template = $setup['template'];
			$html = new TemplateResponse($template['app'], $template['name'], [], 'blank');
		}

		return [
			'widget'   => [
				'id'   => $widget->getId(),
				'name' => $widget->getName()
			],
			'setup'    => $setup,
			'html'     => $html->render(),
			'config'   => $this->getConfig(),
			'position' => $this->getPosition(),
			'enabled'  => ((array_key_exists('x', $this->getPosition())) ? true : false)
		];
	}
}