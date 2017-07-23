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

/** @var array $_ */
script('dashboard', 'settings');
style('dashboard', 'settings');
?>


<form class="dashboard_settings section" id="dashboard_personal_form">
    <h2 class="inlineblock"><?php p($l->t('Dashboard')); ?></h2>

    <div class="hidden icon-checkmark" id="dashboard-changed"><?php p($l->t('')); ?></div>
    <div class="inline-block" id="dashboard-error"><?php p($l->t('')); ?></div>
    <input name="inbox_ssl" type="hidden" value="0"/>
    <label for="inbox_remote_system_name"><?php p($l->t('Mailserver')); ?></label>
    <input id="inbox_remote_system_name" name="inbox_remote_system_name" placeholder="Host" type="text"
           value="<?php p($_['inbox_remote_system_name']); ?>"/>
    :
    <input id="inbox_port" maxlength="5" name="inbox_port" placeholder="Port" type="text"
           value="<?php p($_['inbox_port']); ?>"/>
    <label for="inbox_ssl"><?php p($l->t('SSL')); ?></label>
    <input <?php if ($_['inbox_ssl']): ?>checked="checked"<?php endif; ?> id="inbox_ssl" name="inbox_ssl" type="checkbox" value="1"/>
    <label for="inbox_username"><?php p($l->t('Username')); ?></label>
    <input id="inbox_username" name="inbox_username" type="text" value="<?php p($_['inbox_username']); ?>"/>
    <label for="inbox_password"><?php p($l->t('Password')); ?></label>
    <input id="inbox_password" name="inbox_password" type="password" value="<?php p($_['inbox_password']); ?>"/>
    <input type="submit" value="<?php p($l->t('Save')); ?>"/>
</form>