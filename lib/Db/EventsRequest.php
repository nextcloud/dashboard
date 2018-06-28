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

namespace OCA\Dashboard\Db;


use OCA\Dashboard\Model\Event;

class EventsRequest extends EventsRequestBuilder {


	/**
	 * @param Event $event
	 *
	 * @return int
	 * @throws \Exception
	 */
	public function create(Event $event) {

		try {
			$qb = $this->getEventsInsertSql();
			$qb->setValue('user_id', $qb->createNamedParameter($event->getUserId()))
			   ->setValue('widget_id', $qb->createNamedParameter($event->getWidgetId()))
			   ->setValue('payload', $qb->createNamedParameter(json_encode($event->getPayload())));

			$qb->execute();

			return $qb->getLastInsertId();
		} catch (\Exception $e) {
			throw $e;
		}
	}


	/**
	 * @param Event $event
	 */
	public function deleteEvent(Event $event) {
		$qb = $this->getEventsDeleteSql();
		$this->limitToId($qb, $event->getId());

		$qb->execute();
	}


	/**
	 * @param Event[] $events
	 */
	public function deleteEvents($events) {
// TODO single request for all Ids
		foreach ($events as $event) {
			$this->deleteEvent($event);
		}

	}

	/**
	 * @param string $userId
	 */
	public function reset($userId) {
		$qb = $this->getEventsDeleteSql();
		$this->limitToUserId($qb, $userId);

		$qb->execute();
	}


	/**
	 * @param string $userId
	 * @param int $lastEventId
	 *
	 * @return Event[]
	 */
	public function getEventsByUserId($userId, $lastEventId) {
		$qb = $this->getEventsSelectSql();
		$this->limitToUserId($qb, $userId);
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
	public function getLastEventId() {
		$qb = $this->getEventsSelectSql();
		$qb->orderBy('id', 'desc');
		$qb->setMaxResults(1);

		$cursor = $qb->execute();
		$data = $cursor->fetch();

		if ($data === false) {
			return 0;
		}

		$lastInsertedId = $data['id'];
		$cursor->closeCursor();

		return $lastInsertedId;
	}

}
