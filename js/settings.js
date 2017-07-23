/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 *
 * @copyright regio iT 2017
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