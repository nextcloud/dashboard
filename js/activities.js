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
                        return '<td><span class=\"hidden\">'+ moment.unix(row.timestamp).format('YYYY.MM.DD hh:mm:ss')+'</span>' + moment.unix(row.timestamp).format('DD.MM.YYYY') + '</td>';
                    }
                }
            ]
        });
    });
})(jQuery, OC);