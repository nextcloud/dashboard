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

namespace OCA\Dashboard\Db;


use OCA\Dashboard\Model\WidgetEvent;

class EventsRequest extends EventsRequestBuilder {


	/**
	 * @param WidgetEvent $event
	 *
	 * @return int
	 * @throws \Exception
	 */
	public function create(WidgetEvent $event): int {

		try {
			$qb = $this->getEventsInsertSql();
			$qb->setValue('broadcast', $qb->createNamedParameter($event->getBroadcast()))
			   ->setValue('recipient', $qb->createNamedParameter($event->getRecipient()))
			   ->setValue('widget_id', $qb->createNamedParameter($event->getWidgetId()))
			   ->setValue('unique_id', $qb->createNamedParameter($event->getUniqueId()))
			   ->setValue('payload', $qb->createNamedParameter(json_encode($event->getPayload())));

			$qb->execute();

			return $qb->getLastInsertId();
		} catch (\Exception $e) {
			throw $e;
		}
	}


	/**
	 * @param string $userId
	 */
	public function reset(string $userId) {
		$qb = $this->getEventsDeleteSql();
		$this->limitToUserId($qb, $userId);

		$qb->execute();
	}


	/**
	 * @param string $userId
	 * @param int $lastEventId
	 *
	 * @return WidgetEvent[]
	 */
	public function getUserEvents(string $userId, int $lastEventId): array {
		$qb = $this->getEventsSelectSql();
		$this->limitToBroadcast($qb, WidgetEvent::BROADCAST_USER);
		$this->limitToRecipient($qb, $userId);
		$this->startFromId($qb, $lastEventId);

		$events = [];
		$cursor = $qb->execute();
		while ($data = $cursor->fetch()) {
			$events[] = $this->parseEventsSelectSql($data);
		}
		$cursor->closeCursor();

		return $events;
	}


	/**
	 * @param array $groups
	 * @param int $lastEventId
	 *
	 * @return WidgetEvent []
	 */
	public function getGroupEvents(array $groups, int $lastEventId): array {
		$qb = $this->getEventsSelectSql();
		$this->limitToBroadcast($qb, WidgetEvent::BROADCAST_GROUP);
		$this->limitToRecipient($qb, $groups);
		$this->startFromId($qb, $lastEventId);

		$events = [];
		$cursor = $qb->execute();
		while ($data = $cursor->fetch()) {
			$events[] = $this->parseEventsSelectSql($data);
		}
		$cursor->closeCursor();

		return $events;
	}


	/**
	 * @param int $lastEventId
	 *
	 * @return WidgetEvent []
	 */
	public function getGlobalEvents(int $lastEventId): array {
		$qb = $this->getEventsSelectSql();
		$this->limitToBroadcast($qb, WidgetEvent::BROADCAST_GLOBAL);
		$this->startFromId($qb, $lastEventId);

		$events = [];
		$cursor = $qb->execute();
		while ($data = $cursor->fetch()) {
			$events[] = $this->parseEventsSelectSql($data);
		}
		$cursor->closeCursor();

		return $events;
	}


	/**
	 * return int
	 */
	public function getLastEventId(): int {
		$qb = $this->getEventsSelectSql();
		$qb->orderBy('id', 'desc');
		$qb->setMaxResults(1);

		$cursor = $qb->execute();
		$data = $cursor->fetch();

		if ($data === false) {
			return 0;
		}

		$lastInsertedId = intval($data['id']);
		$cursor->closeCursor();

		return $lastInsertedId;
	}

}
