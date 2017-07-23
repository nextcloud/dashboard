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

        $('#inbox').find('> table').DataTable({
            // DataTables - Features
            info: false,
            paging: false,
            searching: false,
            // DataTables - Data
            ajax: OC.generateUrl('/apps/dashboard/inbox'),
            // DataTables - Options
            order: [[1, 'desc']],
            // DataTables - Columns
            columns: [
                {
                    data: function (row) {
                        // unread mails = bold font
                        if (row.seen == 0) {
                            return '<strong>' + escapeHTML(row.from) + '</strong><br/><a href="' + OC.generateUrl('/apps/rainloop') + '"><strong>' + escapeHTML(row.subject) + '</strong></a>';
                        }
                        else {
                            return escapeHTML(row.from) + '<br/><a href="' + OC.generateUrl('/apps/rainloop') + '">' + escapeHTML(row.subject) + '</a>';
                        }
                    }
                },
                {
                    data: 'udate',
                    render: function (data) {
                        //return escapeHTML(data);
                        return '<td><span class=\"hidden\">'+ moment.unix(data).format('YYYY.MM.DD hh:mm:ss')+'</span>' + moment.unix(data).format('DD.MM.YYYY') + '</td>';
                    }
                }
            ]
        }).on('xhr.dt', function (e, settings, json) {
            if (json && json.message) {
                $(this).find('tfoot').html('<tr><td colspan="2">' + json.message + '</td></tr>');
            }
        });

    });
})(jQuery, OC);