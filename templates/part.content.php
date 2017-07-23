<?php
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
            <div style="width: <?php p($_['storage_info']['relative']); ?>%">
                <p id="quotatext"><?php print_unescaped($l->t('You have used <strong>%s</strong> of the available <strong>%s</strong>', $_['storage_info']['quota'])); ?></p>
            </div>
        </div>
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