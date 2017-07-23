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

(function ($, OC) {

    $(document).ready(function () {

        var callback = function (data) {
            $('.dashboard_settings input, .dashboard_settings select').removeClass('error');
            // todo - User-Feedback
            var icon = $('#dashboard-changed');
                icon.addClass('inlineblock');
                icon.removeClass('hidden');
        };

        $('#dashboard_personal_form').submit(function (eventObject) {
            eventObject.preventDefault();
            var url = OC.generateUrl('/apps/dashboard/personal');
            var data = $(this).serialize();
            $.post(url, data, callback);
        });
        $('#dashboard_admin_form').submit(function (eventObject) {
            eventObject.preventDefault();
            var url = OC.generateUrl('/apps/dashboard/admin');
            var data = $(this).serialize();
            $.post(url, data, callback);
        });
    });
})(jQuery, OC);