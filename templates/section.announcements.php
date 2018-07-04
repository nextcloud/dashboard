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
<?php
if($_['show_announcement']===1) {?>
<section class="dashboard-section span6" style="border-bottom-color: <?php p($_['theming_color']); ?>;<?php if($_['show_wide_announcement']===1) { ?> width: 97.25%;<?php } ?>">
<h1><a data-toggle="myCollapse" data-target="#announcements"><?php p($l->t('Announcements')); ?></a></h1>

<div class="myCollapse in" id="announcements">
    <div></div>
    <?php if ($_['can_create_announcements'] === true): ?>
        <button id="create-announcement"><?php p($l->t('Create announcement')); ?></button>
    <?php endif; ?>
</div>
</section>
<?php }?>
