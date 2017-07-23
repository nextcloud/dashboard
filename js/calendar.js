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

    // a reference to FullCalendar's root namespace
    var FC = $.fullCalendar;
    // the class that all views must inherit from
    var View = FC.View;
    // our subclass
    var CustomView;

    // make a subclass of View
    CustomView = View.extend({

        renderEvents: function (events) {
            events.sort(function (a, b) {
                return a.start - b.start;
            });
            var now = moment().subtract(7, 'days');
            $(this.el).html('');
            for (var i = 0; i < events.length; i++) {
                var event = events[i];
                if (event.end > now) {
                    var elem = $(''.concat('<div >', event.start.format('DD.MM.YYYY HH:mm'), ' ', event.title, '</div>'));
                    elem.addClass('fc-event');
                    elem.css('background-color', event.source.backgroundColor);
                    elem.css('border-color', event.source.borderColor);
                    elem.css('color', event.source.textColor);
                    elem.css('margin-bottom', '1px');
                    $(this.el).append(elem);
                }
            }
        },

        updateTitle: function () {
            var lang = FC.langs[OC.getLocale().substr(0, 2)];
            if (lang !== undefined) {
                this.title = lang.buttonText.list;
            }
        }

    });

    // register our class with the view system
    FC.views.custom = CustomView;

    $(document).ready(function () {
        if (typeof eventSources !== 'undefined') {
            $('#fullcalendar').fullCalendar({
                // General Display
                header: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'month,agendaWeek,list'
                },
                weekNumbers: true,
                height: 'auto',
                eventLimit: 2,
                // Timezone
                timezone: 'local',
                // Views
                views: {
                    list: {
                        type: 'custom'
                    }
                },
                // Agenda Options
                allDaySlot: true,
                slotDuration: '01:00:00',
                minTime: '02:00:00',
                maxTime: '24:00:00',
                // Text/Time Customization
                lang: OC.getLocale().substr(0, 2),
                // Clicking & Hovering
                eventClick: function (calEvent) {
                    var elem = $('#event');
                    elem.html(calEvent.start.format('DD.MM.YYYY HH:mm') + ' - ' + calEvent.end.format('DD.MM.YYYY HH:mm') + '<br/>' + calEvent.title);
                    elem.dialog({
                        open: function(){
                            $(this).closest(".ui-dialog")
                                .find(".ui-dialog-titlebar-close")
                                .html("<span style='margin:0 ' class='ui-button-icon ui-icon ui-icon-closethick'></span>");
                        },
                        modal: true
                    });
                },
                // Event Data
                eventSources: eventSources
            });
        }
    });
})(jQuery, OC);