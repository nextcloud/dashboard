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


// tether (needed by boostrap)
script('dashboard', '../vendor/bordercloud/tether/dist/js/tether.min');
style('dashboard', '../vendor/bordercloud/tether/dist/css/tether');

// bootstrap
script('dashboard', '../vendor/twbs/bootstrap/dist/js/bootstrap.min');
style('dashboard', 'bootstrap-responsive.min');

// datatables
script('dashboard', '../vendor/datatables/datatables/media/js/jquery.dataTables.min');
style('dashboard', '../vendor/datatables/datatables/media/css/jquery.dataTables.min');

// fullcalendar
script('dashboard', '../vendor/fullcalendar/fullcalendar/dist/fullcalendar.min');
script('dashboard', '../vendor/fullcalendar/fullcalendar/dist/locale-all');
style('dashboard', '../vendor/fullcalendar/fullcalendar/dist/fullcalendar.min');

// tinymce
script('dashboard', '../vendor/tinymce/tinymce/tinymce.min');
script('dashboard', '../vendor/tinymce/tinymce/jquery.tinymce.min');

//$languageCode = substr($l->getLanguageCode(), 0, 2);
//if (file_exists("apps/dashboard/vendor/tinymce/tinymce/langs/{$languageCode}.js")) {
//	script('dashboard', "../vendor/tinymce/tinymce/langs/{$languageCode}");
//}

script('dashboard', 'activities');
script('dashboard', 'announcements');
script('dashboard', 'calendar');
script('dashboard', 'inbox');

style('dashboard', 'style');
?>

<input type="hidden" name="nextNonce" id="nextNonce" value="<?php p(
	\OC::$server->getContentSecurityPolicyNonceManager()
				->getNonce()
) ?>"/>

<div id="app">
	<?php /* ?><div id="app-navigation">
      <?php print_unescaped($this->inc('part.navigation')); ?>
      <?php print_unescaped($this->inc('part.settings')); ?>
      </div>--><?php */ ?>

	<div id="app-content">
		<div id="app-content-wrapper">
			<?php print_unescaped($this->inc('part.content')); ?>
		</div>
	</div>
</div>