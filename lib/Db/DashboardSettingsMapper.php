<?php

/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 *
 * @copyright regio iT 2017
 */

namespace OCA\Dashboard\Db;

use OCP\AppFramework\Db\Mapper;
use OCP\IDBConnection;

/**
 * Description of DashboardSettingsMapper
 */
class DashboardSettingsMapper extends Mapper
{
    /**
     * @param IDBConnection $db Instance of the Db abstraction layer
     */
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'dashboard_settings');
    }

    public function findAll($limit = null, $offset = null)
    {
        $sql = 'SELECT * FROM `*PREFIX*dashboard_settings`';
        $erg=$this->findEntities($sql, [], $limit, $offset);
        return $erg;
    }
    public function findOne($id)
    {
        $sql = 'SELECT * FROM `*PREFIX*dashboard_settings` WHERE id = ?';
        return $this->findEntity($sql, [$id]);
    }
}