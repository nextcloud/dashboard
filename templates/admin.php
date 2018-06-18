<?php
/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 * @copyright regio iT 2017
 * @license GNU AGPL version 3 or any later version
 * @contributor tuxedo-rb | TUXEDO Computers GmbH | https://www.tuxedocomputers.com
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

/** @var $l \OCP\IL10N */
/** @var $_ array */
script('dashboard', 'settings');
style('dashboard', 'settings');
?>

<form class="dashboard_settings section" id="dashboard_admin_form">
    <h2 class="inlineblock"><?php p($l->t('Dashboard')); ?></h2>
    <div class="hidden icon-checkmark" id="dashboard-changed"><?php p($l->t('')); ?></div>
    <div class="inline-block" id="dashboard-error"><?php p($l->t('')); ?></div>

    <input name="show_inbox" type="hidden" value="0"/>
    <input name="show_activity" type="hidden" value="0"/>
    <input name="show_announcement" type="hidden" value="0"/>
    <input name="show_calendar" type="hidden" value="0"/>
    <input name="show_quota" type="hidden" value="0" />
    <input name="show_wide_inbox" type="hidden" value="0"/>
    <input name="show_wide_activity" type="hidden" value="0"/>
    <input name="show_wide_announcement" type="hidden" value="0"/>
    <input name="show_wide_calendar" type="hidden" value="0"/>

        <table class="grid activitysettings">
            <thead>
            <tr>
                <th class="small dashboard_select_group" data-select-group="show">
                    <?php p($l->t('Show box')); ?>
                </th>
                <th class="small dashboard_select_group" data-select-group="full">
                    <?php p($l->t('Wide box')); ?>
                </th>
                <th class="small dashboard_select_group" data-select-group="full">
                    <?php p($l->t('Placement')); ?>
                </th>
                <th><span id="dashboard_notifications_msg" class="msg" style="display: none;">save</span></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="small">
                    <input <?php if ($_['show_activity']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_activity" name="show_activity" value="1" class="checkbox">
                    <label for="show_activity">
                    </label>
                </td>
                <td class="small">
                    <input <?php if ($_['show_wide_activity']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_wide_activity" name="show_wide_activity" value="1" class="checkbox">
                    <label for="show_wide_activity">
                    </label>
                </td>
                <td class="small">
                    <select name="activity_position" size="1">
                        <option <?php if ($_['activity_position']==1): ?>selected<?php endif; ?>>1</option>
                        <option <?php if ($_['activity_position']==2): ?>selected<?php endif; ?>>2</option>
                        <option <?php if ($_['activity_position']==3): ?>selected<?php endif; ?>>3</option>
                        <option <?php if ($_['activity_position']==4): ?>selected<?php endif; ?>>4</option>
                    </select>
                    <label for="activity_position">
                    </label>
                </td>
                <td class="dashboard_select_group" data-select-group="activity">
                    <?php p($l->t('Activity')); ?>
                </td>
            </tr>
            <tr>
                <td class="small">
                    <input <?php if ($_['show_inbox']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_inbox" name="show_inbox" value="1" class="checkbox">
                    <label for="show_inbox">
                    </label>
                </td>
                <td class="small">
                    <input <?php if ($_['show_wide_inbox']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_wide_inbox" name="show_wide_inbox" value="1" class="checkbox">
                    <label for="show_wide_inbox">
                    </label>
                </td>
                <td class="small">
                    <select name="inbox_position" size="1">
                        <option <?php if ($_['inbox_position']==1): ?>selected<?php endif; ?>>1</option>
                        <option <?php if ($_['inbox_position']==2): ?>selected<?php endif; ?>>2</option>
                        <option <?php if ($_['inbox_position']==3): ?>selected<?php endif; ?>>3</option>
                        <option <?php if ($_['inbox_position']==4): ?>selected<?php endif; ?>>4</option>
                    </select>
                    <label for="inbox_position">
                    </label>
                </td>
                <td class="dashboard_select_group" data-select-group="inbox">
                    <?php p($l->t('Inbox')); ?>
                </td>
            </tr>
            <tr>
                <td class="small">
                    <input <?php if ($_['show_announcement']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_announcement" name="show_announcement" value="1" class="checkbox">
                    <label for="show_announcement">
                    </label>
                </td>
                <td class="small">
                    <input <?php if ($_['show_wide_announcement']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_wide_announcement" name="show_wide_announcement" value="1" class="checkbox">
                    <label for="show_wide_announcement">
                    </label>
                </td>
                <td class="small">
                    <select name="announcement_position" size="1">
                        <option <?php if ($_['announcement_position']==1): ?>selected<?php endif; ?>>1</option>
                        <option <?php if ($_['announcement_position']==2): ?>selected<?php endif; ?>>2</option>
                        <option <?php if ($_['announcement_position']==3): ?>selected<?php endif; ?>>3</option>
                        <option <?php if ($_['announcement_position']==4): ?>selected<?php endif; ?>>4</option>
                    </select>
                    <label for="announcement_position">
                    </label>
                </td>
                <td class="dashboard_select_group" data-select-group="announcement">
                    <?php p($l->t('Announcements')); ?>
                </td>
            </tr>
            <tr>
                <td class="small">
                    <input <?php if ($_['show_calendar']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_calendar" name="show_calendar" value="1" class="checkbox">
                    <label for="show_calendar">
                    </label>
                </td>
                <td class="small">
                    <input <?php if ($_['show_wide_calendar']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_wide_calendar" name="show_wide_calendar" value="1" class="checkbox">
                    <label for="show_wide_calendar">
                    </label>
                </td>
                <td class="small">
                    <select name="calendar_position" size="1">
                        <option <?php if ($_['calendar_position']==1): ?>selected<?php endif; ?>>1</option>
                        <option <?php if ($_['calendar_position']==2): ?>selected<?php endif; ?>>2</option>
                        <option <?php if ($_['calendar_position']==3): ?>selected<?php endif; ?>>3</option>
                        <option <?php if ($_['calendar_position']==4): ?>selected<?php endif; ?>>4</option>
                    </select>
                    <label for="calendar_position">
                    </label>
                </td>
                <td class="dashboard_select_group" data-select-group="calendar">
                    <?php p($l->t('Calendar')); ?>
                </td>
            </tr>
            <tr>
                <td class="small">
                    <input <?php if ($_['show_quota']): ?>checked="checked"<?php endif; ?> type="checkbox" id="show_quota" name="show_quota" value="1" class="checkbox" />
                    <label for="show_quota">
                    </label>
                </td>
                <td class="small"></td>
                <td class="small"></td>
                <td class="dashboard_select_group" data-select-group="quota">
                    <?php p($l->t('Used space')); ?>
                </td>
            </tr>
            </tbody>
        </table>
        <input type="submit" value="<?php p($l->t('Save')); ?>"/>

</form>
