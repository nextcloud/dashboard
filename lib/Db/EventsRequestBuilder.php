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
use OCP\Dashboard\Model\IWidgetEvent;
use OCP\DB\QueryBuilder\IQueryBuilder;

class EventsRequestBuilder extends CoreRequestBuilder {


	/**
	 * Base of the Sql Insert request
	 *
	 * @return IQueryBuilder
	 */
	protected function getEventsInsertSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->insert(self::TABLE_EVENTS);
		$qb->setValue('creation', $qb->createNamedParameter(time()));

		return $qb;
	}


	/**
	 * Base of the Sql Update request
	 *
	 * @return IQueryBuilder
	 */
	protected function getEventsUpdateSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->update(self::TABLE_EVENTS);

		return $qb;
	}


	/**
	 * Base of the Sql Select request for Shares
	 *
	 * @return IQueryBuilder
	 */
	protected function getEventsSelectSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();

		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$qb->select(
			'e.id', 'e.widget_id', 'e.broadcast', 'e.recipient', 'e.payload', 'e.unique_id',
			'e.creation'
		)
		   ->from(self::TABLE_EVENTS, 'e');

		$this->defaultSelectAlias = 'e';

		return $qb;
	}


	/**
	 * Base of the Sql Delete request
	 *
	 * @return IQueryBuilder
	 */
	protected function getEventsDeleteSql(): IQueryBuilder {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->delete(self::TABLE_EVENTS);

		return $qb;
	}


	/**
	 * @param array $data
	 *
	 * @return WidgetEvent
	 */
	protected function parseEventsSelectSql($data): IWidgetEvent {
		$event = new WidgetEvent($data['widget_id']);
		$event->setId($data['id'])
			  ->setRecipient($data['broadcast'], $data['recipient'])
			  ->setPayload(json_decode($data['payload'], true))
			  ->setUniqueId($data['unique_id'])
			  ->setCreation($data['creation']);

		return $event;
	}

}