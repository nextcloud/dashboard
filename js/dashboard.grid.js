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


var grid = {

	init: function () {
		var options = {
			auto: true,
			float: true
		};

		nav.elements.divGridStack.gridstack(options);
		nav.elements.gridStack = nav.elements.divGridStack.data('gridstack');
		nav.elements.gridStack.setStatic(true);
	},


	fillGrid: function () {
		nav.elements.gridStack.removeAll();
		for (var i = 0; i < curr.widgets.length; i++) {
			var item = curr.widgets[i];
			if (item.enabled === false) {
				continue;
			}

			grid.addWidget(item, false);
		}

		nav.elements.gridStack.setStatic(true);
	},


	addWidget: function (item, auto) {

		if (auto === undefined) {
			auto = true;
		}

		var widgetContent = $('<div>', {
			class: 'grid-stack-item-content',
			'data-widget-id': item.widget.id
		});

		widgetContent.append(this.generateWidgetHeader(item));
		widgetContent.append($('<div>', {class: 'widget-content'}).html(item.html));

		widgetContent.on('mousedown', function () {
			if (!curr.settingsShown) {
				return;
			}
			settings.displayWidgetSettings($(this).attr('data-widget-id'));
		});

		var widget = $('<div>', {
			'data-widget-id': item.widget.id
		}).append(widgetContent);
		var position = grid.initPosition(item);

		nav.elements.gridStack.addWidget(widget,
			position.x, position.y, position.width, position.height, auto);
	},


	removeWidget: function (widgetId) {
		net.deleteWidget(widgetId);

		var widget = grid.getWidgetFromId(widgetId);
		if (widget === null) {
			return;
		}

		nav.elements.gridStack.removeWidget(widget);
	},


	generateWidgetHeader: function (item) {

		var headerCloseIcon = $('<div>', {class: 'icon-close-white widget-close-icon'});
		headerCloseIcon.on('mousedown', function (event) {
			event.stopPropagation();
			grid.removeWidget(item.widget.id);
		});

		if (!curr.settingsShown) {
			headerCloseIcon.hide();
		}

		var widgetHeader = $('<div>', {class: 'widget-header'}).text(item.widget.name);
		widgetHeader.append(headerCloseIcon);

		if (item.setup.template.icon !== undefined) {
			var headerIcon = $('<div>', {class: item.setup.template.icon + '-white widget-header-icon'});
			widgetHeader.append(headerIcon);
		}

		return widgetHeader;
	},


	initPosition: function (item) {
		var position = {};
		if (item.setup.size !== undefined) {
			position = item.setup.size;
		}

		if (item.position.x !== undefined) {
			position.x = item.position.x;
		}

		if (item.position.y !== undefined) {
			position.y = item.position.y;
		}

		if (item.position.width !== undefined) {
			position.width = item.position.width;
		}

		if (item.position.height !== undefined) {
			position.height = item.position.height;
		}

		position = grid.fixPosition(position);

		return position;
	},


	fixPosition: function (position) {
		if (position === undefined) {
			position = {};
		}

		if (position.width === undefined) {
			position.width = 3;
		}

		if (position.height === undefined) {
			position.height = 2;
		}

		return position;
	},


	getWidgetFromId: function (widgetId) {
		var widget = null;
		nav.elements.divGridStack.children().each(function () {
			if ($(this).attr('data-widget-id') === widgetId) {
				widget = $(this);
			}
		});

		return widget;
	},


	saveGrid: function () {
		var currGrid = _.map($('.grid-stack > .grid-stack-item:visible'), function (el) {
			var node = $(el).data('_gridstack_node');
			return {
				x: node.x,
				y: node.y,
				width: node.width,
				height: node.height,
				widgetId: $(el).attr('data-widget-id')
			};
		}, this);

		net.saveGrid(currGrid);
	},


	hideSettings: function () {
		nav.elements.divGridStack.find('.widget-close-icon').each(function () {
			$(this).stop().hide(150);
		});
	},

	showSettings: function () {
		nav.elements.divGridStack.find('.widget-close-icon').each(function () {
			$(this).stop().show(150);
		});
	}
};
