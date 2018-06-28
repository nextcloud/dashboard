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
	 * @constructs Fortunes
	 */
	var Fortunes = function () {

		var fortunes = {

			divFortune: null,

			init: function () {
				fortunes.divFortune = $('#widget-fortunes');
				fortunes.getFortune();
			},


			getFortune: function () {
				var request = {
					widget: 'fortunes',
					request: 'getFortune'
				};

				net.requestWidget(request, fortunes.displayFortune);
			},


			displayFortune: function (result) {
				if (result.result === 'fail') {
					return;
				}

				var fortune = result.value.fortune;
				fortunes.divFortune.fadeOut(150, function () {
					$(this).text(fortune).fadeIn(150);
				});
			},

			push: function (payload) {
				if (payload.fortune === undefined) {
					return;
				}

				fortunes.divFortune.fadeOut(150, function () {
					$(this).text(payload.fortune).fadeIn(150);
				});
			}

		};

		$.extend(Fortunes.prototype, fortunes);
	};

	OCA.DashBoard.Fortunes = Fortunes;
	OCA.DashBoard.fortunes = new Fortunes();

})();


