/**
 * Nextcloud - Dashboard app
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018, Maxence Lange <maxence@artificial-owl.com>
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
/** global: net */
/** global: grid */
/** global: settings */
/** global: curr */


var nav = {

	elements: {
		divNoWidget: null,
		divNewWidget: null,
		buttonNewWidget: null,
		elWidgetList: null,
		divGridStack: null,
		gridStack: null
	},


	init: function () {
		nav.initElements();
		net.getWidgets(nav.onGetWidgets);
	},


	initElements: function () {
		nav.elements.divNoWidget = $('#dashboard-nowidget');
		nav.elements.divNewWidget = $('.dashboard-newwidget');
		nav.elements.buttonNewWidget = $('#dashboard-newwidget');
		nav.elements.divGridStack = $('.grid-stack');

		nav.elements.buttonNewWidget.on('click', nav.showWidgetsList);

		nav.elements.divNewWidget.fadeOut(0).delay(3000).fadeTo(150, 0.35);
		nav.elements.divNewWidget.on('mouseover', function () {
			$(this).stop().fadeTo(150, 0.7);
		}).on('mouseout', function () {
			$(this).stop().fadeTo(150, 0.35);
		}).on('click', function () {
			nav.showWidgetsList();
		});

		$(window).click(function () {
			settings.hideWidgetMenu();
		});

	},

	showWidgetsList: function () {
		nav.generateWidgetsList();

		$('#app-content').append(nav.elements.elWidgetList);
		nav.elements.elWidgetList.ocdialog({
			closeOnEscape: true,
			modal: true,
			width: 600,
			height: 500,
			title: 'Add a new widget',
			buttons: {}
		});
	},


	onGetWidgets: function (result) {
		curr.widgets = result;

		settings.firstInstall();
		grid.fillGrid();
	},


	generateWidgetsList: function () {

		nav.elements.elWidgetList = $('<div>');

		var count = 0;
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];
			if (item.config.enabled) {
				continue;
			}

			var div = $('<div>', {
				class: 'widget-list-row',
				'data-widget-id': item.widget.id
			}).append($('<div>', {
				href: '#',
				class: 'widget-list-icon ' + ((item.template.icon) ? item.template.icon : 'icon-widget')
			})).append($('<div>', {class: 'widget-list-text'})
				.append($('<div>', {class: 'widget-list-name'}).text(item.widget.name))
				.append($('<div>', {class: 'widget-list-desc'}).text(item.widget.description))
			);

			div.fadeTo(0, 0.65);
			div.on('mouseover', function () {
				$(this).stop().fadeTo(150, 1);
			}).on('mouseout', function () {
				$(this).stop().fadeTo(150, 0.65);
			});

			div.on('click', function () {
				var item = settings.getWidget($(this).attr('data-widget-id'));
				if (item === null || item.config.enabled) {
					return
				}

				grid.addWidget(item);
				nav.elements.elWidgetList.ocdialog('close');
			});

			nav.elements.elWidgetList.append(div);
			count++;
		}
	},


	executeFunction: function (functionName, context) {
		var args = Array.prototype.slice.call(arguments, 2);
		var namespaces = functionName.split(".");
		var func = namespaces.pop();
		for (var i = 0; i < namespaces.length; i++) {
			if (context[namespaces[i]] === undefined) {
				console.log('Unknown function \'' + functionName + '\'');
				return undefined;
			}
			context = context[namespaces[i]];
		}
		return context[func].apply(context, args);
	}


};

