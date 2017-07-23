<input type="hidden" name="nextNonce" id="nextNonce" value="<?php p(
	\OC::$server->getContentSecurityPolicyNonceManager()
				->getNonce()
) ?>"/>
<?php

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