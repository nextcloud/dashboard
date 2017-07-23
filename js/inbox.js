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