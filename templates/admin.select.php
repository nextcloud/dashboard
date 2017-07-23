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
?>

<select id="<?php p($_['name']); ?>" name="<?php p($_['name']); ?>" title="<?php p($_['name']); ?>">
    <option></option>
    <option value="activities"<?php if ('activities' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Recently uploaded files')); ?>
    </option>
    <option value="inbox"<?php if ('inbox' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Inbox')); ?>
    </option>
    <option value="announcements"<?php if ('announcements' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Announcements')); ?>
    </option>
    <option value="calendar"<?php if ('calendar' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Next events')); ?>
    </option>
</select>