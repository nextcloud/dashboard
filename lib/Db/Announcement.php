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

use OCP\AppFramework\Db\Entity;

/**
 * Description of Announcement
 */
class Announcement extends Entity
{
    public $content;
    public $title;
    public $expiration;
    public $userId;
    public $createdAt;
}