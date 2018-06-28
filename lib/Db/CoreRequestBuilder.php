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


use Doctrine\DBAL\Query\QueryBuilder;
use OCA\Dashboard\Service\ConfigService;
use OCA\Dashboard\Service\MiscService;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\IL10N;

class CoreRequestBuilder {

	const TABLE_EVENTS = 'dashboard_events';

	/** @var IDBConnection */
	protected $dbConnection;

	/** @var IL10N */
	protected $l10n;

	/** @var ConfigService */
	protected $configService;

	/** @var MiscService */
	protected $miscService;

	/** @var string */
	protected $defaultSelectAlias;


	/**
	 * CoreRequestBuilder constructor.
	 *
	 * @param IL10N $l10n
	 * @param IDBConnection $connection
	 * @param ConfigService $configService
	 * @param MiscService $miscService
	 */
	public function __construct(
		IL10N $l10n, IDBConnection $connection, ConfigService $configService,
		MiscService $miscService
	) {
		$this->l10n = $l10n;
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
	 * Limit the request to the OwnerId
	 *
	 * @param IQueryBuilder $qb
	 * @param string $userId
	 */
	protected function limitToWidgetId(IQueryBuilder &$qb, $userId) {
		$this->limitToDBField($qb, 'widget_id', $userId);
	}


	/**
	 * Limit to the type
	 *
	 * @param IQueryBuilder $qb
	 * @param string $providerId
	 */
	protected function limitToUserId(IQueryBuilder &$qb, $providerId) {
		$this->limitToDBField($qb, 'user_id', $providerId);
	}


	/**
	 * Limit from id
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
	 * @param string|integer|array $values
	 */
	private function limitToDBField(IQueryBuilder &$qb, $field, $values) {
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



