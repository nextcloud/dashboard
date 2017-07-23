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

<?php foreach ($_['announcements'] as $index => $announcement): ?>
    <div>
        <span style="float: right; font-size: .8em">
            <?php p($announcement->userId); ?> <?php p($announcement->createdAt); ?>
            <?php if ($_['can_edit_announcements'] === true): ?>
                <img class="icon_edit" title="<?php p($l->t('Edit announcement')); ?>" src="<?php print_unescaped(image_path('dashboard', 'edit.svg')); ?>" data-id="<?php p($announcement->id); ?>">
            <?php endif; ?>
            <?php if ($_['can_delete_announcements'] === true): ?>
                <img class="icon" title="<?php p($l->t('Delete announcement')); ?>" src="<?php print_unescaped(image_path('dashboard', 'delete.svg')); ?>" data-id="<?php p($announcement->id); ?>">
            <?php endif; ?>
        </span>

        <h2><?php p($announcement->title); ?></h2>
        <?php print_unescaped($announcement->content); ?>
    </div>
    <hr>
<?php endforeach; ?>