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
 * @contributor tuxedo-rb | TUXEDO Computers GmbH | https://www.tuxedocomputers.com
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

namespace OCA\Dashboard\AppInfo;

$app = new Application();
$container = $app->getContainer();

$container->getServer()->getNavigationManager()->add(function () use ($container) {
    $urlGenerator = $container->getServer()->getURLGenerator();
    $l10n = $container->query('OCP\IL10N');
    return [
        'id' => 'dashboard',
        // sorting weight for the navigation. The higher the number, the higher
        // will it be listed in the navigation
        'order' => -1,
        // the route that will be shown on startup
        'href' => $urlGenerator->linkToRoute('dashboard.page.index'),
        // the icon that will be shown in the navigation
        // this file needs to exist in img/
        'icon' => $urlGenerator->imagePath('dashboard', 'dashboard.svg'),
        // the title of your application. This will be used in the
        // navigation or on the settings page of your app
        'name' => $l10n->t('Dashboard')
    ];
});
