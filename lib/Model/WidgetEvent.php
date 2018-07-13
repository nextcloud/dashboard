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


use OCP\Dashboard\Model\IWidgetEvent;

class WidgetEvent implements IWidgetEvent, \JsonSerializable {


	/** @var int */
	private $id;

	/** @var string */
	private $widgetId;

	/** @var string */
	private $broadcast;

	/** @var string */
	private $recipient;

	/** @var array */
	private $payload = [];

	/** @var string */
	private $uniqueId = '';

	/** @var int */
	private $creation;

	/**
	 * WidgetEvent constructor.
	 *
	 * @param $widgetId
	 */
	public function __construct(string $widgetId) {
		$this->widgetId = $widgetId;
	}


	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId(int $id): IWidgetEvent {
		$this->id = $id;

		return $this;
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
	 * @return $this
	 */
	public function setWidgetId(string $widgetId): IWidgetEvent {
		$this->widgetId = $widgetId;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getBroadcast(): string {
		return $this->broadcast;
	}


	/**
	 * @return string
	 */
	public function getRecipient(): string {
		return $this->recipient;
	}


	/**
	 * @param string $broadcast
	 * @param string $recipient
	 *
	 * @return $this
	 */
	public function setRecipient(string $broadcast, string $recipient = ''): IWidgetEvent {
		$this->broadcast = $broadcast;
		$this->recipient = $recipient;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getPayload(): array {
		return $this->payload;
	}

	/**
	 * @param array $payload
	 *
	 * @return $this
	 */
	public function setPayload(array $payload): IWidgetEvent {
		$this->payload = $payload;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getUniqueId(): string {
		return $this->uniqueId;
	}

	/**
	 * @param string $uniqueId
	 *
	 * @return $this
	 */
	public function setUniqueId(string $uniqueId): IWidgetEvent {
		$this->uniqueId = $uniqueId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getCreation(): int {
		return $this->creation;
	}

	/**
	 * @param int $creation
	 *
	 * @return $this
	 */
	public function setCreation(int $creation): IWidgetEvent {
		$this->creation = $creation;

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
		return [
			'id'        => $this->getId(),
			'widgetId'  => $this->getWidgetId(),
			'broadcast' => $this->getBroadcast(),
			'recipient' => $this->getRecipient(),
			'payload'   => $this->getPayload(),
			'uniqueId'  => $this->getUniqueId(),
			'creation'  => $this->getCreation()
		];
	}
}