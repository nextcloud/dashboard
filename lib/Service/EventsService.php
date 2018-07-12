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

namespace OCA\Dashboard\Service;

use Exception;
use OC\App\AppManager;
use OC_App;
use OCA\Dashboard\Db\EventsRequest;
use OCA\Dashboard\Exceptions\WidgetDoesNotExistException;
use OCA\Dashboard\Exceptions\WidgetIsNotCompatibleException;
use OCA\Dashboard\Exceptions\WidgetIsNotUniqueException;
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetEvent;
use OCA\Dashboard\Model\WidgetFrame;
use OCA\Dashboard\Model\WidgetRequest;
use OCP\AppFramework\QueryException;
use OCP\PreConditionNotMetException;

class EventsService {

	/** @var EventsRequest */
	private $eventsRequest;

	/** @var ConfigService */
	private $configService;

	/** @var MiscService */
	private $miscService;


	/**
	 * ProviderService constructor.
	 *
	 * @param EventsRequest $eventsRequest
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 */
	public function __construct(
		EventsRequest $eventsRequest, ConfigService $configService, MiscService $miscService
	) {
		$this->eventsRequest = $eventsRequest;
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * @param string $widgetId
	 * @param string|array $users
	 * @param array $payload
	 * @param string $uniqueId
	 */
	public function createUserEvent($widgetId, $users, $payload, $uniqueId = '') {
		if (!is_array($users)) {
			$users = [$users];
		}

		if ($uniqueId === '') {
			$uniqueId = uniqid();
		}

		foreach ($users as $userId) {
			$event = new WidgetEvent($widgetId);
			$event->setRecipient(WidgetEvent::BROADCAST_USER, $userId);
			$event->setPayload($payload);
			$event->setUniqueId($uniqueId);

			$this->pushEvent($event);
		}
	}


	/**
	 * @param string $widgetId
	 * @param string|array $groups
	 * @param array $payload
	 * @param string $uniqueId
	 */
	public function createGroupEvent($widgetId, $groups, $payload, $uniqueId = '') {
		if (!is_array($groups)) {
			$groups = [$groups];
		}

		if ($uniqueId === '') {
			$uniqueId = uniqid();
		}

		foreach ($groups as $groupId) {
			$event = new WidgetEvent($widgetId);
			$event->setRecipient(WidgetEvent::BROADCAST_GROUP, $groupId);
			$event->setPayload($payload);
			$event->setUniqueId($uniqueId);

			$this->pushEvent($event);
		}
	}


	/**
	 * @param string $widgetId
	 * @param array $payload
	 * @param string $uniqueId
	 */
	public function createGlobalEvent($widgetId, $payload, $uniqueId = '') {
		if ($uniqueId === '') {
			$uniqueId = uniqid();
		}

		$event = new WidgetEvent($widgetId);
		$event->setRecipient(WidgetEvent::BROADCAST_GLOBAL);
		$event->setPayload($payload);
		$event->setUniqueId($uniqueId);

		$this->pushEvent($event);
	}


	/**
	 * @param WidgetEvent $event
	 */
	public function pushEvent(WidgetEvent $event) {
		try {
//			$this->miscService->log('push event: ' . json_encode($event));
			$this->eventsRequest->create($event);
		} catch (Exception $e) {
		}
	}

	/**
	 * @param $userId
	 *
	 * @param int $lastEventId
	 *
	 * @return WidgetEvent[]
	 */
	public function getEvents($userId, $lastEventId) {
		$userEvents = $this->eventsRequest->getUserEvents($userId, $lastEventId);
//$groupEvents = $this->eventsRequest->getEventsByGroups($userId, $lastEventId);
		$groupEvents = [];
		$globalEvents = $this->eventsRequest->getGlobalEvents($lastEventId);

		return array_merge($userEvents, $groupEvents, $globalEvents);
	}


	public function getLastEventId() {
		return $this->eventsRequest->getLastEventId();
	}


}