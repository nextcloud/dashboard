<?php
/** @var array $_ */
?>
<?php
if($_['show_calendar']===1)    {?>
    <section class="dashboard-section span6" <?php if($_['show_wide_calendar']===1)    {?> style="width: 97.25%"
        <?php
    } ?>>

<h1><a data-toggle="myCollapse" data-target="#fullcalendar"><?php p($l->t('Next events')); ?></a></h1>
<div class="myCollapse in" id="fullcalendar"></div>
<script type="text/javascript" src="<?php p($_['dashboard.calendar.get_event_sources']); ?>"></script>
</section>
    <?php
}
?>