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


var curr = {
	settingsShown: false,
	widgetsShown: false,
	settingsWidget: '',
	widgets: [],
	jobs: []
};

var settings = {

	options: [],

	firstInstall: function () {

		nav.elements.divNoWidget.stop().fadeOut(150);
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];
			if (item.enabled) {
				return;
			}
		}

		nav.elements.divNoWidget.stop().fadeIn(150);
	},


	moreWidgetToInstall: function () {
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];
			if (!item.enabled) {
				return true;
			}
		}
		return false;
	},


	getWidget: function (widgetId) {
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];
			if (item.widget.id === widgetId) {
				return item;
			}
		}

		return null;
	},


	updateWidgetEnabledStatus: function (widgetId, enabled) {
		for (var i = 0; i < curr.widgets.length; i++) {
			if (curr.widgets[i].widget.id === widgetId) {
				curr.widgets[i].enabled = enabled;
			}
		}
	},


	generateSettingsPanel: function (item) {
		var html = '<input type="checkbox" id="test1" class="checkbox checkbox--white" checked="checked">' +
			'<label for="test1">Selected</label><br>' +
			'<input type="checkbox" id="test2" class="checkbox checkbox--white">' +
			'<label for="test2">Unselected</label><br>' +
			'<input type="checkbox" id="test3" class="checkbox checkbox--white" disabled="disabled">' +
			'<label for="test3">Disabled</label><br>' +
			'<input type="checkbox" id="test4" class="checkbox checkbox--white">' +
			'<label for="test4">Hovered</label><br>';

		return html;
	},


	displayWidgetMenu: function (divMenu, item) {
		var currShown = curr.settingsShown;
		settings.hideWidgetMenu();

		if (currShown === item.widget.id) {
			return;
		}

		var menuUl = divMenu.find('ul');

		menuUl.empty();

		if (item.setup.menu !== undefined) {
			for (var i = 0; i < item.setup.menu.length; i++) {
				var menu = item.setup.menu[i];

				var liMenu = $('<li>').append($('<a>', {
					href: '#',
					class: menu.icon
				}).append($('<span>').text(menu.text)));
				liMenu.on('click', {function: menu.function}, function (event) {
					nav.executeFunction(event.data.function, window);
				}).on('mousedown mouseup', function (event) {
					event.stopPropagation();
				});
				menuUl.append(liMenu);
			}
		}

		if (item.setup.settings !== undefined) {
			var liSettings = $('<li>').append($('<a>', {
				href: '#',
				class: 'icon-settings'
			}).append($('<span>').text('Configure this widget')));
			liSettings.on('click', function () {
				//nav.showWidgetsList();
			}).on('mousedown mouseup', function (event) {
				event.stopPropagation();
			});
			menuUl.append(liSettings);
		}

		if (settings.moreWidgetToInstall()) {
			var liAddWidget = $('<li>').append($('<a>', {
				href: '#',
				class: 'icon-add'
			}).append($('<span>').text('Add a widget')));
			liAddWidget.on('click', function () {
				nav.showWidgetsList();
			}).on('mousedown mouseup', function (event) {
				event.stopPropagation();
			});
			menuUl.append(liAddWidget);
		}

		var liRemoveWidget = $('<li>').append($('<a>', {
			href: '#',
			class: 'icon-delete'
		}).append($('<span>').text('Remove this widget')));
		liRemoveWidget.on('click', function () {
			grid.removeWidget(item.widget.id)
		}).on('mousedown mouseup', function (event) {
			event.stopPropagation();
		});
		menuUl.append(liRemoveWidget);

		divMenu.fadeIn(150);
		curr.settingsShown = item.widget.id;
	},


	hideWidgetMenu: function () {
		curr.settingsShown = '';
		nav.elements.divGridStack.find('.popovermenu').each(function () {
			$(this).fadeOut(150);
		});
		// divHeader.fadeOut(150);
	},

	// displayWidgetSettings: function (widgetId) {
	// 	if (curr.settingsWidget !== '') {
	// 		if (widgetId !== curr.settingsWidget) {
	// 			settings.hideWidgetSettings(widgetId);
	// 		}
	// 		return;
	// 	}
	//
	// 	var item = settings.getWidget(widgetId);
	// 	if (item.setup.options === undefined) {
	// 		return;
	// 	}
	//
	// 	curr.settingsWidget = widgetId;
	//
	// 	var configureHeader = $('<li>').append(
	// 		$('<a>', {href: '#'}).text('Configure ' + item.widget.name));
	//
	// 	settings.displaySettingsLi(configureHeader);
	//
	// 	for (var i = 0; i < item.setup.options.length; i++) {
	// 		var option = item.setup.options[i];
	//
	// 		if (option.type === 'input') {
	// 			settings.addSettingsInput(option);
	// 		}
	//
	// 		if (option.type === 'checkbox') {
	// 			settings.addSettingsCheckbox(option);
	// 		}
	// 	}
	// },


	// hideWidgetSettings: function (widgetId) {
	// 	curr.settingsWidget = '';
	//
	// 	// var divLi = nav.elements.divDashSettings.children('li');
	// 	// divLi.each(function (index) {
	// 	// 	if ($(this).attr('id') === 'dash-widget-new') {
	// 	// 		return;
	// 	// 	}
	// 	// 	if (widgetId !== undefined && widgetId !== '' && index === (divLi.length - 1)) {
	// 	// 		$(this).stop().fadeTo(150, 0, function () {
	// 	// 			$(this).remove();
	// 	// 			settings.displayWidgetSettings(widgetId);
	// 	// 		});
	// 	// 		return;
	// 	// 	}
	// 	//
	// 	// 	$(this).stop().fadeTo(150, 0, function () {
	// 	// 		$(this).remove();
	// 	// 	});
	// 	// });
	// },


	// addSettingsTitle: function (title) {
	// 	var settingsTitle = $('<li>').append($('<div>', {class:
	// 'app-navigation-entry-bullet'})).append( $('<a>', {href: '#'}).text(title));
	// settings.displaySettingsLi(settingsTitle); },   addSettingsInput: function (option) {
	// settings.addSettingsTitle(option.title);  var buttonEdit = $('<button>', {class: 'icon-rename'});
	// buttonEdit.on('click', function () {
	// $(this).closest('li.switch-edition-mode').addClass('editing'); });  var settingsInput = $('<li>',
	// {class: 'switch-edition-mode'}); settingsInput.append($('<a>', {href: '#'}).text('empty'));
	// settingsInput.append($('<div>', {class: 'app-navigation-entry-utils'}).append(
	// $('<ul>').append($('<li>', {class: 'app-navigation-entry-utils-menu-button'}).append(
	// buttonEdit))));  var input = $('<input>', { type: 'text', value: '', placeholder:
	// ((option.placeholder !== 'undefined') ? option.placeholder : '') });  var inputClose =
	// $('<input>', { type: 'submit', value: '', class: 'icon-close' }); inputClose.on('click', function
	// () { $(this).closest('li.switch-edition-mode').removeClass('editing'); });  var inputSave =
	// $('<input>', { type: 'submit', value: '', class: 'icon-checkmark' }); inputSave.on('click',
	// function () { $(this).closest('li.switch-edition-mode').removeClass('editing');
	// console.log('SAVE'); });  settingsInput.append($('<div>', {class:
	// 'app-navigation-entry-edit'}).append($('<form>').append(
	// input).append(inputClose).append(inputSave))); settings.displaySettingsLi(settingsInput); },
	// addSettingsCheckbox: function (option) { // { // 	"name": "test_long_lorem", // 	"title":
	// "Longer Lorem", // 	"type": "checkbox", // 	"default": true // } },   displaySettingsLi:
	// function (div) { div.css('display', 'inherit').fadeTo(0, 0);
	// nav.elements.divDashSettings.append(div); div.fadeTo(150, 1); }

	broadcastPushWidget: function (result) {
		if (result.data === undefined) {
			return;
		}

		for (var i = 0; i < result.data.length; i++) {
			var item = result.data[i];
			var widget = settings.getWidget(item.widgetId);
			if (!widget.enabled || widget.setup.push === undefined) {
				continue;
			}

			nav.executeFunction(widget.setup.push, window, item.payload);
		}
	}
};
