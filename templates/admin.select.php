<?php
/** @var array $_ */
?>

<select id="<?php p($_['name']); ?>" name="<?php p($_['name']); ?>" title="<?php p($_['name']); ?>">
    <option></option>
    <option value="activities"<?php if ('activities' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Recently uploaded files')); ?>
    </option>
    <option value="inbox"<?php if ('inbox' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Inbox')); ?>
    </option>
    <option value="announcements"<?php if ('announcements' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Announc  ements')); ?>
    </option>
    <option value="calendar"<?php if ('calendar' === $_[$_['name']]): ?> selected<?php endif; ?>>
        <?php p($l->t('Next events')); ?>
    </option>
</select>