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
 * Description of DashboardSettings
 *
 */
class DashboardSettings extends Entity
{
    public $id;
    public $key;
    public $value;
}