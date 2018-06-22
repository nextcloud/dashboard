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
Util::addScript(Application::APP_NAME, 'jquery.flip');

Util::addScript(Application::APP_NAME, 'dashboard.navigation');
Util::addScript(Application::APP_NAME, 'dashboard.net');
Util::addScript(Application::APP_NAME, 'dashboard.settings');
Util::addScript(Application::APP_NAME, 'dashboard.grid');
Util::addScript(Application::APP_NAME, 'dashboard');

Util::addStyle(Application::APP_NAME, 'gridstack');
Util::addStyle(Application::APP_NAME, 'dashboard');

?>


<div id="app-content">

	<div class="container-fluid">
		<div class="grid-stack">
		</div>
	</div>

	<div id="dashboard-footer">
		<div id="dashboard-settings-open">
			<div id="dashboard-action-save" class="icon-save"></div>
			<div id="dashboard-action-add" class="icon-add"></div>
			<div id="dashboard-menu-widgets" class="popovermenu">
				<ul>
					<li>
						<a href="#" class="icon-details">
							<span>Details</span>
						</a>
					</li>
					<li>
						<button class="icon-details">
							<span>Details</span>
						</button>
					</li>
					<li>
						<button>
							<span class="icon-details"></span>
							<span>Details</span>
						</button>
					</li>
					<li>
						<a>
							<span class="icon-details"></span>
							<span>Details</span>
						</a>
					</li>
					<li>
						<a href="#" class="icon-details">
							<span>Details</span>
						</a>
					</li>
					<li>
						<button class="icon-details">
							<span>Details</span>
						</button>
					</li>
					<li>
						<button>
							<span class="icon-details"></span>
							<span>Details</span>
						</button>
					</li>
					<li>
						<a>
							<span class="icon-details"></span>
							<span>Details</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div id="dashboard-settings-close">
			<div id="dashboard-action-settings" class="icon-settings"></div>
			<div id="dashboard-settings-first" class="popovermenu">
				<ul>
					<li>
						<a href="#" class="icon-info">
							<span>Click here to add your first widget</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
