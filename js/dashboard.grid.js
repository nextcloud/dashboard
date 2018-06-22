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

		settings.updateWidgetEnabledStatus(item.widget.id, true);

		if (auto === undefined) {
			auto = true;
		}

		var widgetDiv = $('<div>', {
			class: 'grid-stack-item-content',
			'data-widget-id': item.widget.id
		});

		widgetDiv.append(this.generateWidgetHeader(item));
		var widgetContent = $('<div>', {class: 'widget-content'});
		widgetDiv.append(widgetContent);

		var widgetContentFront = $('<div>', {class: 'front'}).html(item.html);
		widgetContent.append(widgetContentFront)

		if (item.setup.settings !== undefined) {
			var widgetContentBack = $('<div>', {class: 'back'}).html(
				settings.generateSettingsPanel(item));
			widgetContent.append(widgetContentBack)
			widgetContent.flip({
				trigger: 'manual',
				axis: 'y'
			});
		}

		var widget = $('<div>', {
			'data-widget-id': item.widget.id
		}).append(widgetDiv);

		var position = grid.initPosition(item);

		nav.elements.gridStack.addWidget(widget,
			position.x, position.y, position.width, position.height, auto,
			position.minWidth, position.maxWidth, position.minHeight, position.maxHeight);

		nav.updateAddWidgetsIcon();
	},


	removeWidget: function (widgetId) {
		net.deleteWidget(widgetId);

		var widget = grid.getWidgetFromId(widgetId);
		if (widget === null) {
			return;
		}

		nav.elements.gridStack.removeWidget(widget);
		settings.updateWidgetEnabledStatus(widgetId, false);

		nav.updateAddWidgetsIcon();
	},


	configureWidget: function (widgetId) {
		var widget = grid.getWidgetFromId(widgetId);
		if (widget === null) {
			return;
		}

		widget.find('.widget-content').flip('toggle');
	},

	generateWidgetHeader: function (item) {

		var headerIcons = $('<div>', {class: 'widget-right-icons'});
		if (!curr.settingsShown) {
			headerIcons.hide();
		}

		var headerCloseIcon = $('<div>', {class: 'icon-close-white'});
		headerCloseIcon.on('mousedown', function (event) {
			event.stopPropagation();
			nav.hideWidgetsList();
			grid.removeWidget(item.widget.id);
		});
		headerIcons.append(headerCloseIcon);

		if (item.setup.settings !== undefined) {
			var headerSettingsIcon = $('<div>', {class: 'icon-settings-white'});
			headerSettingsIcon.on('mousedown', function (event) {
				event.stopPropagation();
				nav.hideWidgetsList();
				grid.configureWidget(item.widget.id);
			});
			headerIcons.append(headerSettingsIcon);
		}

		var widgetHeader = $('<div>', {class: 'widget-header'}).text(item.widget.name);
		widgetHeader.append(headerIcons);

		if (item.setup.template.icon !== undefined) {
			var widgetIcon = $('<div>', {class: item.setup.template.icon + '-white widget-header-icon'});
			widgetHeader.append(widgetIcon);
		}
		headerIcons.fadeOut(0);

		return widgetHeader;
	},


	initPosition: function (item) {
		var position = {};
		if (item.setup.size !== undefined) {
			position = this.defaultPosition(item.setup.size);
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


	defaultPosition: function (size) {
		var position = {};
		if (size.default !== undefined) {
			position = size.default;
		}

		if (size.min !== undefined) {
			if (size.min.width !== undefined) {
				position.minWidth = size.min.width;
			}
			if (size.min.height !== undefined) {
				position.minHeight = size.min.height;
			}
		}

		if (size.max !== undefined) {
			if (size.max.width !== undefined) {
				position.maxWidth = size.max.width;
			}
			if (size.max.height !== undefined) {
				position.maxHeight = size.max.height;
			}
		}

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

		if (position.minHeight === undefined) {
			position.minHeight = 2;
		}

		if (position.minWidth === undefined) {
			position.minWidth = 2;
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
		nav.elements.gridStack.setStatic(true);
		nav.elements.divGridStack.find('.widget-right-icons').each(function () {
			$(this).stop().fadeOut(150);
		});

		nav.elements.divGridStack.find('.widget-content').each(function () {
			if ($(this).data('flip-model')) {
				$(this).flip(false);
			}
		});
	},

	showSettings: function () {
		nav.elements.gridStack.setStatic(false);
		nav.elements.divGridStack.find('.widget-right-icons').each(function () {
			$(this).stop().fadeIn(150);
		});
	}
};
