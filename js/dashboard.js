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



/*
 * DashBoard - Full text search framework for Nextcloud
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018
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

/** global: OCA */
/** global: settings */
/** global: nav */
/** global: net */


(function () {

	/**
	 * @constructs DashBoard
	 */
	var DashBoard = function () {
		$.extend(DashBoard.prototype, settings);
		$.extend(DashBoard.prototype, nav);
		$.extend(DashBoard.prototype, net);

		nav.init();
	};

	OCA.DashBoard = DashBoard;
	OCA.DashBoard.dashBoard = new DashBoard();

})();




$(function () {
	var options = {
		float: true
	};
	var curr = {
		settingShown: false
	};

	$('.grid-stack').gridstack(options);

	new function () {
		this.items = [
			{
				x: 0,
				y: 0,
				width: 2,
				height: 2
			},
			{
				x: 3,
				y: 1,
				width: 1,
				height: 2
			},
			{
				x: 4,
				y: 1,
				width: 1,
				height: 1
			},
			{
				x: 2,
				y: 3,
				width: 3,
				height: 1
			},
//                    {x: 1, y: 4, width: 1, height: 1},
//                    {x: 1, y: 3, width: 1, height: 1},
//                    {x: 2, y: 4, width: 1, height: 1},
			{
				x: 2,
				y: 5,
				width: 1,
				height: 1
			}
		];

		this.grid = $('.grid-stack').data('gridstack');

		this.addNewWidget = function () {
			var node = this.items.pop() || {
				x: 12 * Math.random(),
				y: 5 * Math.random(),
				width: 1 + 3 * Math.random(),
				height: 1 + 3 * Math.random()
			};
			this.grid.addWidget($('<div><div class="grid-stack-item-content" /><div/>'),
				node.x, node.y, node.width, node.height);
			return false;
		}.bind(this);

		$('#add-new-widget').click(this.addNewWidget);
	};



});