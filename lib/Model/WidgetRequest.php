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


use JsonSerializable;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;

class WidgetRequest implements IWidgetRequest, JsonSerializable {


	/** @var IDashboardWidget */
	private $widget = null;

	/** @var string */
	private $widgetId = '';

	/** @var string */
	private $request = '';

	/** @var array */
	private $result = [];


	/**
	 * WidgetRequest constructor.
	 *
	 * @param string $widgetId
	 */
	public function __construct(string $widgetId) {
		$this->widgetId = $widgetId;
	}


	/**
	 * @return string
	 */
	public function getWidgetId(): string {
		return $this->widgetId;
	}

	/**
	 * @param string $widgetId
	 *
	 * @return $this;
	 */
	public function setWidgetId(string $widgetId): IWidgetRequest {
		$this->widgetId = $widgetId;

		return $this;
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
	public function setWidget(IDashboardWidget $widget): IWidgetRequest {
		$this->widget = $widget;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getRequest(): string {
		return $this->request;
	}

	/**
	 * @param string $request
	 *
	 * @return $this
	 */
	public function setRequest(string $request): IWidgetRequest {
		$this->request = $request;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getResult(): array {
		return $this->result;
	}

	/**
	 * @param array $result
	 *
	 * @return $this
	 */
	public function setResult(array $result): IWidgetRequest {
		$this->result = $result;

		return $this;
	}


	/**
	 * @param string $key
	 * @param string $result
	 *
	 * @return $this
	 */
	public function addResult(string $key, string $result): IWidgetRequest {
		$this->result[$key] = $result;

		return $this;
	}


	/**
	 * @since 15.0.0
	 *
	 * @param string $key
	 * @param array $result
	 *
	 * @return $this
	 */
	public function addResultArray(string $key, array $result): IWidgetRequest {
		$this->result[$key] = $result;

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

		return [
			'widget' => [
				'id'          => $widget->getId(),
				'name'        => $widget->getName(),
				'description' => $widget->getDescription()
			]
		];
	}


	public static function fromJson($json) {
		$arr = json_decode($json, true);
		$request = new WidgetRequest($arr['widget']);
		$request->setRequest($arr['request']);

//		$request->setType($arr['keyword']);

		return $request;
	}

}
