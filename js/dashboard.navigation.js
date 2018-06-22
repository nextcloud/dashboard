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
		divSettingsOpen: null,
		divSettingsClose: null,
		divFirstInstall: null,
		divMenuWidgets: null,
		iconSettings: null,
		iconAdd: null,
		iconSave: null,
		divGridStack: null,
		gridStack: null
	},


	init: function () {
		nav.initElements();
		net.getWidgets(nav.onGetWidgets);
	},


	initElements: function () {
		nav.elements.divSettingsOpen = $('#dashboard-settings-open');
		nav.elements.divSettingsClose = $('#dashboard-settings-close');
		nav.elements.divFirstInstall = $('#dashboard-settings-first');
		nav.elements.divMenuWidgets = $('#dashboard-menu-widgets')
		nav.elements.iconSettings = $('#dashboard-action-settings');
		nav.elements.iconAdd = $('#dashboard-action-add');
		nav.elements.iconSave = $('#dashboard-action-save');
		nav.elements.divGridStack = $('.grid-stack');

		nav.elements.divSettingsOpen.fadeOut(0);
		nav.elements.iconSettings.on('click', nav.showSettings);
		nav.elements.divFirstInstall.on('click', function () {
			nav.showSettings(true);
		});
		nav.elements.iconSave.on('click', nav.hideSettings);
		nav.elements.iconAdd.on('click', function (event) {
			event.stopPropagation();
			nav.showWidgetsList();
		});

		$(window).click(function () {
			nav.hideWidgetsList();
		});
	},


	showSettings: function (showWidgetsList) {
		curr.settingsShown = true;
		nav.elements.divSettingsClose.stop().fadeOut(150, function () {
			nav.elements.divSettingsOpen.stop().fadeIn(150);
		});

		grid.showSettings();
		nav.elements.divFirstInstall.stop().fadeOut(150);

		if (showWidgetsList === true) {
			nav.showWidgetsList();
		}
	},

	hideSettings: function () {
		curr.settingsShown = false;
		nav.elements.divSettingsOpen.stop().fadeOut(150, function () {
			nav.elements.divSettingsClose.stop().fadeIn(150);
		});

		nav.hideWidgetsList();
		settings.firstInstall();
		grid.saveGrid();
		grid.hideSettings();
	},


	showWidgetsList: function () {
		nav.fillWidgetsList();

		curr.widgetsShown = true;
		nav.elements.divMenuWidgets.stop().fadeIn(150);
	},

	hideWidgetsList: function () {
		curr.widgetsShown = false;
		nav.elements.divMenuWidgets.stop().fadeOut(150);
	},


	onGetWidgets: function (result) {
		curr.widgets = result;

		settings.firstInstall();
		nav.updateAddWidgetsIcon();
		grid.fillGrid();
	},


	updateAddWidgetsIcon: function () {
		var count = 0;
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];

			if (item.enabled) {
				continue;
			}
			count++;
		}

		if (count === 0) {
			nav.elements.iconAdd.fadeTo(150, 0.2);
		} else {
			nav.elements.iconAdd.fadeTo(150, 0.7);
		}

	},


	fillWidgetsList: function () {

		var menuUl = nav.elements.divMenuWidgets.children('ul');
		menuUl.empty();

		var count = 0;
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];

			if (item.enabled) {
				continue;
			}

			var div = $('<li>', {
				'data-widget-id': item.widget.id
			}).append($('<a>', {
				href: '#',
				class: (item.setup.template.icon) ? item.setup.template.icon : 'icon-widget'
			}).append($('<span>').text(item.widget.name)));

			div.on('click', function () {
				var item = settings.getWidget($(this).attr('data-widget-id'));
				if (item === null || item.enabled) {
					return
				}

				grid.addWidget(item);
			});

			menuUl.append(div);
			count++;
		}

		if (count === 0) {
			var divNoWidget = $('<li>').append($('<a>', {
				href: '#',
				class: 'icon-info'
			}).append($('<span>').text('All available widget are already on your dashboard')));
			menuUl.append(divNoWidget);
		}
	}


};

