<?php
/** @var array $_ */
?>
<?php
if($_['show_announcement']===1)    {?>
    <section class="dashboard-section span6" <?php if($_['show_wide_announcement']===1)    {?> style="width: 97.25%"
        <?php
    } ?>>

<h1><a data-toggle="myCollapse" data-target="#announcements"><?php p($l->t('Announcements')); ?></a></h1>

<div class="myCollapse in" id="announcements">
    <div></div>
    <?php if ($_['can_create_announcements'] === true): ?>
        <button id="create-announcement"><?php p($l->t('Create announcement')); ?></button>
    <?php endif; ?>
</div>
    </section>
    <?php
}
?>