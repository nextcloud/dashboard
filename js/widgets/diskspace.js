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

/** global: OCA */
/** global: net */


(function () {

	/**
	 * @constructs DiskSpace
	 */
	var DiskSpace = function () {

		var diskspace = {

			init: function () {
				$('#diskspace-progress').fadeOut(0);

				diskspace.getDiskSpace();
			},


			getDiskSpace: function () {
				var request = {
					widget: 'diskspace',
					request: 'getDiskSpace'
				};

				net.requestWidget(request, diskspace.displayDiskSpace);
			},


			test: function () {
				console.log('delayed job !');
			},

			displayDiskSpace: function (result) {
				if (result.result === 'fail') {
					return;
				}

				var used = OC.Util.humanFileSize(parseInt(result.value.diskSpace.used, 10), true);
				var total = OC.Util.humanFileSize(parseInt(result.value.diskSpace.total, 10), true);
				var percent = Math.round(100 * used / total);

				$('#diskspace-progress').stop().fadeIn(150);
				$('#diskspace-used').text(used);
				$('#diskspace-total').text(total);
				$('#diskspace-progress-used').css('width', percent + '%');
			}

		};

		$.extend(DiskSpace.prototype, diskspace);
	};

	OCA.DashBoard.DiskSpace = DiskSpace;
	OCA.DashBoard.diskspace = new DiskSpace();

})();


