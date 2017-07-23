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
<?php
if($_['show_inbox']===1)    {?>
<section class="dashboard-section span6" <?php if($_['show_wide_inbox']===1)    {?> style="width: 97.25%"
<?php
} ?>
>
<h1><a data-toggle="myCollapse" data-target="#inbox"><?php p($l->t('Inbox')); ?></a></h1>

<div class="myCollapse in" id="inbox">
    <table class="hover">
        <thead>
        <tr>
            <th><?php p($l->t('Message')); ?></th>
            <th><?php p($l->t('Received')); ?></th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
    </table>
</div>
    </section>
<?php
}
?>