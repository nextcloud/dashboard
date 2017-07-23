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
 * Description of AnnouncementMapper
 */
class AnnouncementMapper extends Mapper
{
    /**
     * @param IDBConnection $db Instance of the Db abstraction layer
     */
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'dashboard_announcements');
    }

    public function findAll($limit = null, $offset = null)
    {
        $sql = 'SELECT * FROM `*PREFIX*dashboard_announcements` WHERE expiration >= CURDATE() ORDER BY created_at DESC';
        return $this->findEntities($sql, [], $limit, $offset);
    }

    public function findOne($id)
    {
        $sql = 'SELECT * FROM `*PREFIX*dashboard_announcements` WHERE id = ?';
        return $this->findEntity($sql, [$id]);
    }
}