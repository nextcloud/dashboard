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

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\Dashboard\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'routes' => [
		['name' => 'Navigation#navigate', 'url' => '/', 'verb' => 'GET'],
		['name' => 'Navigation#getWidgets', 'url' => '/widgets', 'verb' => 'GET'],
		['name' => 'Navigation#deleteWidget', 'url' => '/widget', 'verb' => 'DELETE'],
		['name' => 'Navigation#saveGrid', 'url' => '/widgets/grid', 'verb' => 'POST'],
		['name' => 'Widget#requestWidget', 'url' => '/widget/request', 'verb' => 'GET'],
		['name' => 'Widget#pushWidget', 'url' => '/widget/push', 'verb' => 'POST'],

		//	['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'activity#index', 'url' => '/activities', 'verb' => 'GET'],
		['name' => 'announcement#index', 'url' => '/announcements', 'verb' => 'GET'],
		['name' => 'announcement#create', 'url' => '/announcements', 'verb' => 'POST'],
		[
			'name' => 'announcement#destroy', 'url' => '/announcements/{id}',
			'verb' => 'DELETE', 'requirements' => [
			'id' => '\d+',
		]
		],
		[
			'name'         => 'announcement#edit', 'url' => '/announcements/{id}',
			'verb'         => 'GET',
			'requirements' => [
				'id' => '\d+',
			]
		],
		[
			'name' => 'announcement#updateEdit', 'url' => '/announcements/{id}',
			'verb' => 'POST', 'requirements' => [
			'id' => '\d+',
		]
		],

		['name' => 'calendar#get_event_sources', 'url' => '/event_sources', 'verb' => 'GET'],

		[
			'name' => 'calendar#get_events', 'url' => '/events/{calendarid}',
			'verb' => 'GET', 'requirements' => [
			'calendarid' => '\d+|shared_events',
		]
		],
		['name' => 'inbox#index', 'url' => '/inbox', 'verb' => 'GET'],
		['name' => 'personal#save', 'url' => '/personal', 'verb' => 'POST'],
		['name' => 'admin#save', 'url' => '/admin', 'verb' => 'POST'],
	]
];