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
if($_['show_activity']===1) {?>
<section class="dashboard-section span6" style="border-bottom-color: <?php p($_['theming_color']); ?>;<?php if($_['show_wide_activity']===1) { ?> width: 97.25%;<?php } ?>">
<h1><a data-toggle="myCollapse" data-target="#activities"><?php p($l->t('Recently uploaded files')); ?></a></h1>
<div class="myCollapse in" id="activities">
    <table class="hover" style="width: 100%;">
        <thead>
        <tr>
            <th style="width: 50%;"><?php p($l->t('File')); ?></th>
            <th style="width: 25%;"><?php p($l->t('Uploaded by')); ?></th>
            <th style="width: 25%;"><?php p($l->t('Uploaded at')); ?></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
</section>
<?php }?>
