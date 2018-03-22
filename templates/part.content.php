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

/** @var array $_ */
?>
<div class="row-fluid">
        <?php print_unescaped($this->inc("section.{$_['panel_0']}")); ?>
        <?php print_unescaped($this->inc("section.{$_['panel_1']}")); ?>
</div>

<div class="row-fluid">
    <?php print_unescaped($this->inc("section.{$_['panel_2']}")); ?>
    <?php print_unescaped($this->inc("section.{$_['panel_3']}")); ?>
</div>

<div class="row-fluid">
    <section class="dashboard-section">
        <h1><a data-toggle="myCollapse" data-target="#quota"><?php p($l->t('Used space')); ?></a></h1>

        <div id="quota" class="myCollapse in">
            <div id="quota-used" style="width: <?php p($_['storage_info']['relative']); ?>%"></div>
            <div id="quota-limit"></div>
            <p id="quota-text"><?php print_unescaped($l->t('You have used <strong>%s</strong> of the available <strong>%s</strong>', $_['storage_info']['quota'])); ?></p>
        </div>
        <div style="clear: both;"></div>
    </section>
</div>

<?php if ($_['can_create_announcements'] === true): ?>
    <div id="announcement-form">
        <form>
            <label for="announcement-title"><?php p($l->t('Title')); ?></label>
            <input id="announcement-title" name="title" type="text">
            <label for="announcement-expiration"><?php p($l->t('Expiration date')); ?></label>
            <input id="announcement-expiration" name="expiration" type="date" value="<?php p($_['default_announcement_expiration_value']); ?>">
            <br>
            <textarea id="announcement-content" name="content" title="<?php p($l->t('Content')); ?>"></textarea>
        </form>
    </div>
<?php endif; ?>
<div id="event"></div>

<?php /*if ($_['can_edit_announcements'] === true):*/ ?>
    <div id="announcement-form-edit">
        <form>
            <label for="announcement-title-edit"><?php p($l->t('Title')); ?></label>
            <input id="announcement-title-edit" name="title" type="text">
            <label for="announcement-expiration-edit"><?php p($l->t('Expiration date')); ?></label>
            <input id="announcement-expiration-edit" name="expiration" type="date" value="<?php p($_['default_announcement_expiration_value']); ?>">
            <br>
            <textarea id="announcement-content-edit" name="content" title="<?php p($l->t('Content')); ?>"></textarea>
        </form>
    </div>
<?php /* endif;*/ ?>

<div id="event"></div>
