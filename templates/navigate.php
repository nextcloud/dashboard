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

use OCA\Dashboard\AppInfo\Application;
use OCP\Util;

Util::addScript(Application::APP_NAME, 'gridstack.all');

Util::addScript(Application::APP_NAME, 'dashboard.navigation');
Util::addScript(Application::APP_NAME, 'dashboard.net');
Util::addScript(Application::APP_NAME, 'dashboard.settings');
Util::addScript(Application::APP_NAME, 'dashboard');

Util::addStyle(Application::APP_NAME, 'gridstack');
Util::addStyle(Application::APP_NAME, 'dashboard');

?>


<div id="app-navigation">
	<ul>
		<li id="dash-plugins-new" class="collapsible">

			<!-- This is optional -->
			<button class="collapse"></button>

			<a href="#" class="icon-dashboard">Add new plugin</a>
			<ul id="dash-plugins-list">
			</ul>
		</li>

	</ul>
</div>


<div id="app-content">

	<div id="dashboard-header">
		<div id="dashboard-settings" class="icon-settings"></div>
	</div>

	<div class="container-fluid">

		<!--		<div>-->
		<!--			<a class="btn btn-default" id="add-new-widget" href="#">Add Widget</a>-->
		<!--		</div>-->
		<!-- -->
		<!--		<br/>-->
		<!-- -->
		<!--		<div class="grid-stack">-->
		<!--		</div>-->
		<!--	</div>-->

		<div class="grid-stack">
			<div class="grid-stack-item"
				 data-gs-x="0" data-gs-y="0"
				 data-gs-width="4" data-gs-height="2">
				<div class="grid-stack-item-content">test 1</div>
			</div>
			<div class="grid-stack-item"
				 data-gs-x="4" data-gs-y="0"
				 data-gs-width="4" data-gs-height="4">
				<div class="grid-stack-item-content"> test 2</div>
			</div>
		</div>
	</div>
