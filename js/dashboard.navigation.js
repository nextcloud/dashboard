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
		divFirstInstall: null,
		divSaveInstruction: null,
		iconSettings: null,
		divDashSettings: null,
		divWidgetsList: null,
		divWidgetsListNew: null,
		divGridStack: null,
		gridStack: null
	},


	init: function () {
		nav.initElements();
		net.getWidgets(nav.onGetWidgets);
	},


	initElements: function () {
		nav.elements.divSettings = $('#app-navigation');
		nav.elements.divFirstInstall = $('#dashboard-settings-first');
		nav.elements.divSaveInstruction = $('#dashboard-save')
		nav.elements.iconSettings = $('#dashboard-settings');
		nav.elements.divDashSettings = $('#dash-settings');
		nav.elements.divWidgetsList = $('#dash-widgets-list');
		nav.elements.divWidgetsListNew = $('#dash-widget-new');
		nav.elements.divGridStack = $('.grid-stack');

		nav.elements.divSettings.hide();
		nav.elements.iconSettings.on('click', nav.switchSettings);
		nav.elements.divFirstInstall.on('click', nav.switchSettings);
		nav.elements.divSaveInstruction.on('click', nav.switchSettings);

		nav.elements.divWidgetsListNew.on('click', function () {
			if ($(this).hasClass('open')) {
				$(this).removeClass('open');
			} else {
				$(this).addClass('open');
			}
		});
	},


	switchSettings: function () {
		if (curr.settingsShown) {
			nav.hideSettings();
		} else {
			nav.showSettings();
		}
	},

	hideSettings: function () {
		curr.settingsShown = false;
		nav.elements.gridStack.setStatic(true);
		nav.elements.divSettings.hide(150);
		settings.hideWidgetSettings();
		settings.firstInstall();
		grid.saveGrid();
		grid.hideSettings();
		nav.elements.divSaveInstruction.stop().show().fadeTo(150, 0, function () {
			$(this).hide()
		});
	},

	showSettings: function () {
		curr.settingsShown = true;
		nav.elements.gridStack.setStatic(false);
		nav.elements.divSettings.show(150);
		nav.elements.divSaveInstruction.stop().show().fadeTo(150, 0.6);
		grid.showSettings();
		nav.elements.divFirstInstall.stop().fadeTo(150, 0, function () {
			$(this).hide();
		});
	},

	onGetWidgets: function (result) {
		curr.widgets = result;

		settings.firstInstall();
		nav.fillWidgetsList();
		grid.fillGrid();
	},


	fillWidgetsList: function () {

		nav.elements.divWidgetsList.empty();
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];

			var div = $('<li>', {
				'data-id': item.widget.id
			}).append($('<a>', {
				href: '#',
				class: (item.setup.template.icon) ? item.setup.template.icon : 'icon-widget'
			}).text(item.widget.name));

			div.on('click', function () {
				var item = settings.getWidget($(this).attr('data-id'));
				if (item === null) {
					return
				}

				grid.addWidget(item);
			});

			nav.elements.divWidgetsList.append(div);
		}
	}


};

