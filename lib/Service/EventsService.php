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


namespace OCA\Dashboard\Service;

use Exception;
use OCA\Dashboard\Db\EventsRequest;
use OCA\Dashboard\Model\WidgetEvent;
use OCP\Dashboard\Service\IEventsService;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\IUserManager;

class EventsService implements IEventsService {

	/** @var IUserManager */
	private $userManager;

	/** @var IGroupManager */
	private $groupManager;

	/** @var EventsRequest */
	private $eventsRequest;

	/** @var ConfigService */
	private $configService;

	/** @var MiscService */
	private $miscService;


	/**
	 * EventsService constructor.
	 *
	 * @param IUserManager $userManager
	 * @param IGroupManager $groupManager
	 * @param EventsRequest $eventsRequest
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 */
	public function __construct(
		IUserManager $userManager, IGroupManager $groupManager, EventsRequest $eventsRequest,
		ConfigService $configService, MiscService $miscService
	) {
		$this->userManager = $userManager;
		$this->groupManager = $groupManager;
		$this->eventsRequest = $eventsRequest;
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * @param string $widgetId
	 * @param array $users
	 * @param array $payload
	 * @param string $uniqueId
	 */
	public function createUsersEvent(
		string $widgetId, array $users, array $payload, string $uniqueId = ''
	) {
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
	 * @param array $groups
	 * @param array $payload
	 * @param string $uniqueId
	 */
	public function createGroupsEvent(
		string $widgetId, array $groups, array $payload, string $uniqueId = ''
	) {
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
	public function createGlobalEvent(string $widgetId, array $payload, string $uniqueId = '') {
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
	private function pushEvent(WidgetEvent $event) {
		try {
			$this->eventsRequest->create($event);
		} catch (Exception $e) {
			/** we do nohtin */
		}
	}


	/**
	 * @param string $userId
	 * @param int $lastEventId
	 *
	 * @return WidgetEvent[]
	 */
	public function getEvents(string $userId, int $lastEventId): array {
		$userEvents = $this->eventsRequest->getUserEvents($userId, $lastEventId);

//		$this->miscService->log(
//			'# GET EVENTS ## > ' . json_encode($this->getGroupsFromUserId($userId))
//		);
		$groupEvents =
			$this->eventsRequest->getGroupEvents($this->getGroupsFromUserId($userId), $lastEventId);
		$globalEvents = $this->eventsRequest->getGlobalEvents($lastEventId);

		return array_merge($userEvents, $groupEvents, $globalEvents);
	}


	/**
	 * @return int
	 */
	public function getLastEventId(): int {
		return $this->eventsRequest->getLastEventId();
	}


	/**
	 * @param string $userId
	 *
	 * @return array
	 */
	private function getGroupsFromUserId(string $userId): array {
		$user = $this->userManager->get($userId);
		$groups = $this->groupManager->getUserGroups($user);

		return array_keys(
			array_map(
				function(IGroup $group) {
					return $group->getGID();
				}, $groups
			)
		);
	}

}
