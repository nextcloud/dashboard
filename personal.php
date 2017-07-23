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

$app = new \OCA\Dashboard\AppInfo\Application();

/** @var OCA\Dashboard\Controller\PersonalController $controller */
$controller = $app->getContainer()
				  ->query('OCA\Dashboard\Controller\PersonalController');

return $controller->index()
				  ->render();


