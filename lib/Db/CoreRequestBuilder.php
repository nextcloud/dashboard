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


use Doctrine\DBAL\Query\QueryBuilder;
use OCA\Dashboard\Service\ConfigService;
use OCA\Dashboard\Service\MiscService;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class CoreRequestBuilder {

	const TABLE_SETTINGS = 'dashboard_settings';
	const TABLE_EVENTS = 'dashboard_events';

	/** @var IDBConnection */
	protected $dbConnection;

	/** @var ConfigService */
	protected $configService;

	/** @var MiscService */
	protected $miscService;

	/** @var string */
	protected $defaultSelectAlias;


	/**
	 * CoreRequestBuilder constructor.
	 *
	 * @param IDBConnection $connection
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 */
	public function __construct(
		IDBConnection $connection, ConfigService $configService, MiscService $miscService
	) {
		$this->dbConnection = $connection;
		$this->configService = $configService;
		$this->miscService = $miscService;
	}


	/**
	 * Limit the request to the Id
	 *
	 * @param IQueryBuilder $qb
	 * @param int $id
	 */
	protected function limitToId(IQueryBuilder &$qb, $id) {
		$this->limitToDBField($qb, 'id', $id);
	}


	/**
	 * Limit the request to the WidgetId
	 *
	 * @param IQueryBuilder $qb
	 * @param string $widgetId
	 */
	protected function limitToWidgetId(IQueryBuilder &$qb, $widgetId) {
		$this->limitToDBField($qb, 'widget_id', $widgetId);
	}


	/**
	 * Limit to the UserId
	 *
	 * @param IQueryBuilder $qb
	 * @param string $userId
	 */
	protected function limitToUserId(IQueryBuilder &$qb, $userId) {
		$this->limitToDBField($qb, 'user_id', $userId);
	}


	/**
	 * Limit to the broadcast
	 *
	 * @param IQueryBuilder $qb
	 * @param string $broadcast
	 */
	protected function limitToBroadcast(IQueryBuilder &$qb, $broadcast) {
		$this->limitToDBField($qb, 'broadcast', $broadcast);
	}


	/**
	 * Limit to the recipient
	 *
	 * @param IQueryBuilder $qb
	 * @param string|array $recipient
	 */
	protected function limitToRecipient(IQueryBuilder &$qb, $recipient) {
		$this->limitToDBField($qb, 'recipient', $recipient);
	}


	/**
	 * start from Id
	 *
	 * @param IQueryBuilder $qb
	 * @param int $id
	 */
	protected function startFromId(IQueryBuilder &$qb, $id) {
		$expr = $qb->expr();
		$pf = ($qb->getType() === QueryBuilder::SELECT) ? $this->defaultSelectAlias . '.' : '';
		$field = $pf . 'id';

		$qb->andWhere($expr->gt($field, $qb->createNamedParameter($id)));
	}


	/**
	 * @param IQueryBuilder $qb
	 * @param string $field
	 * @param string|array $values
	 */
	private function limitToDBField(IQueryBuilder &$qb, string $field, $values) {
		$expr = $qb->expr();
		$pf = ($qb->getType() === QueryBuilder::SELECT) ? $this->defaultSelectAlias . '.' : '';
		$field = $pf . $field;

		if (!is_array($values)) {
			$values = [$values];
		}

		$orX = $expr->orX();
		foreach ($values as $value) {
			$orX->add($expr->eq($field, $qb->createNamedParameter($value)));
		}

		$qb->andWhere($orX);
	}

}



