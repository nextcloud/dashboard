/*
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

/** global: OC */


var nav = {

	elements: {
		divSettings: null,
		iconSettings: null,
		dashPluginsList: null
	},


	init: function () {
		nav.initElements();
		net.getPlugins(nav.onGetPlugins);
	},


	initElements: function () {
		nav.elements.divSettings = $('#app-navigation');
		nav.elements.iconSettings = $('#dashboard-settings');
		nav.elements.dashPluginsList = $('#dash-plugins-list');
		nav.elements.dashPluginsListNew = $('#dash-plugins-new');

		nav.elements.divSettings.hide();
		nav.elements.iconSettings.on('click', function () {
			if (curr.settingsShown) {
				curr.settingsShown = false;
				nav.elements.divSettings.hide(150);
			} else {
				curr.settingsShown = true;
				nav.elements.divSettings.show(150);
			}
		});


		nav.elements.dashPluginsListNew.on('click', function () {
			if ($(this).hasClass('open')) {
				$(this).removeClass('open');
			} else {
				$(this).addClass('open');
			}
		});
	},


	onGetPlugins: function (result) {
		for (var i = 0; i < result.length; i++) {
			var item = result[i];

			console.log(JSON.stringify(item));

			if (item.template.css !== undefined) {
				console.log('---' + JSON.stringify(item.template));
				OC.addStyle(item.template.app, item.template.css);
			}

			var div = $('<li>').append($('<a>', {
				href: '#',
				class: (item.template.icon) ? item.template.icon : 'icon-plugin'
			}).text(item.plugin.name));

			nav.elements.dashPluginsList.append(div);
		}
	}


};

