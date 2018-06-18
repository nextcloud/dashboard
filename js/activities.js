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
        $("[data-toggle='myCollapse']").click(function( ev ) {
            ev.preventDefault();
            var target;
            if (this.hasAttribute('data-target')) {
                target = $(this.getAttribute('data-target'));
            } else {
                target = $(this.getAttribute('href'));
            }
            target.toggleClass("in");
        });

        $('#activities').find('> table').DataTable(
        {
            // DataTables - Features
            info: false,
            paging: false,
            searching:false,
            // DataTables - Data
            ajax: OC.generateUrl('/apps/dashboard/activities'),
            // DataTables - Options
            order: [[2, 'desc']],
            // DataTables - Columns
            columns: [
                {
                    data: function (row) {
                        var filename = row.object_name;
                        filename = filename.replace("/", "");
                        var lastindex = filename.lastIndexOf("/") + 1;
                        filename = filename.substr(lastindex);
                        if(filename.length>30)  {
                            filename = filename.substr(0,30);
                            filename = filename+'...';
                        }
                        return filename.link(row.link);
                    }
                },
                {
                    data: function (row) {
                            return row.user;
                    }
                },
                {
                    data: function (row, type) {
                        var sharedWithMe='';
                        if(row.type==='shared_with_by'){
                            sharedWithMe='<img src=\"../../../core/img/actions/shared.svg\" alt=\"shared\" title=\"share date\" id=\"shared\" />';
                        }
                        return '<td><span class=\"hidden\">' + moment.unix(row.timestamp).format('YYYY.MM.DD hh:mm:ss')+'</span>' + moment.unix(row.timestamp).format('YYYY-MM-DD') + sharedWithMe + '</td>';
                    }
                }
            ]
        });
    });
})(jQuery, OC);