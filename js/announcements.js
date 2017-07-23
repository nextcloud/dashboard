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

    var Announcements = {};

    Announcements.reload = function () {
        var announcementID="";
        var url = OC.generateUrl('/apps/dashboard/announcements');
        $.get(url, function (data) {
            var elem = $('#announcements').find('> div');
            elem.html(data);
            elem.find('img[class=icon]').click(function () {
                if (confirm('Wirklich löschen?')) {
                    $.ajax({
                        method: 'DELETE',
                        success: function () {
                            Announcements.reload();
                        },
                        url: url + '/' + $(this).data('id')
                    });
                }
            });

            // Edit
            var announcementFormEdit = $('#announcement-form-edit').dialog({
                autoOpen: false,
                open: function(){
                    $(this).closest(".ui-dialog")
                        .find(".ui-dialog-titlebar-close")
                        .html("<span style='margin:0 ' class='ui-button-icon ui-icon ui-icon-closethick'></span>");
                },
                buttons: [{
                    click: function () {
                        var url = OC.generateUrl('/apps/dashboard/announcements');
                        var data = $('#announcement-form-edit').find('> form').serializeArray();
                        $.ajax({
                            method: 'POST',
                            data: data,
                            success: function () {
                                announcementFormEdit.dialog('close');
                                Announcements.reload();
                            },
                            url: url+'/'+announcementID
                        });
                    },
                    text: t('dashboard', 'Save changes')
                }],
                close: function () {
                    $('#announcement-form-edit').find('> form')[0].reset();
                },
                height: 480,
                modal: false,
                width: 640
            });
            $('#announcement-content-edit').tinymce({
                height: 260,
                plugins: [
                    'image',
                    'link',
                    'media',
                    'textcolor',
                    'colorpicker',
                    'fullscreen'
                ],
                toolbar: "styleselect | undo redo | removeformat | bold italic underline |  aligncenter alignjustify | bullist numlist outdent indent | link | image | print | fontsizeselect | forecolor | backcolor",
                resize: false,
                media_live_embeds: true,
                relative_urls: false
            });

            elem.find('img[class=icon_edit]').click(function () {
                announcementID=$(this).data('id');
                // call data-entrys from choosen announcement
                $.ajax({
                    method: 'GET',
                    success: function (data) {
                        fillEntrys(data);
                    },
                    url: url + '/' + $(this).data('id')
                });
                announcementFormEdit.dialog('open');
            });

        });
    };
    function fillEntrys(data)   {
        //parse data-object in json
        var entrys = JSON.stringify(data);
        var json = JSON.parse(entrys);
        document.getElementById("announcement-title-edit").value=json["title"];
        tinymce.get('announcement-content-edit').setContent(json["content"]);
        document.getElementById("announcement-expiration-edit").value = json["expiration"]
    }

    $(document).ready(function () {
        // News
        var announcementForm = $('#announcement-form').dialog({
            autoOpen: false,
            open: function(){
                $(this).closest(".ui-dialog")
                    .find(".ui-dialog-titlebar-close")
                    .html("<span style='margin:0 ' class='ui-button-icon ui-icon ui-icon-closethick'></span>");
            },
            buttons: [{
                click: function () {
                    var url = OC.generateUrl('/apps/dashboard/announcements');
                    var data = $('#announcement-form').find('> form').serializeArray();
                    $.post(url, data, function () {
                        announcementForm.dialog('close');
                        Announcements.reload();
                    });
                },
                text: t('dashboard', 'Create announcement')
            }],
            close: function () {
                $('#announcement-form').find('> form')[0].reset();
            },
            height: 480,
            modal: false,
            width: 640
        });

        $('#announcement-content').tinymce({
            height: 260,
            plugins: [
                'image',
                'link',
                'media',
                'textcolor',
                'colorpicker',
                'fullscreen'
            ],
            toolbar: "styleselect | undo redo | removeformat | bold italic underline |  aligncenter alignjustify | bullist numlist outdent indent | link | image | print | fontsizeselect | forecolor | backcolor",
            resize: false,
            media_live_embeds: true,
            relative_urls: false
        });

        $('#create-announcement').click(function () {
            announcementForm.dialog('open');
        });
        Announcements.reload();
    });
})(jQuery, OC);