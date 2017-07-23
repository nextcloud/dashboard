<?php
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