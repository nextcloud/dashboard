<?php
/** @var array $_ */
script('dashboard', 'settings');
style('dashboard', 'settings');
?>


<form class="dashboard_settings section" id="dashboard_personal_form">
    <h2 class="inlineblock"><?php p($l->t('Dashboard')); ?></h2>

    <div class="hidden icon-checkmark" id="dashboard-changed"><?php p($l->t('')); ?></div>
    <div class="inline-block" id="dashboard-error"><?php p($l->t('')); ?></div>
    <input name="inbox_ssl" type="hidden" value="0"/>
    <label for="inbox_remote_system_name"><?php p($l->t('Mailserver')); ?></label>
    <input id="inbox_remote_system_name" name="inbox_remote_system_name" placeholder="Host" type="text"
           value="<?php p($_['inbox_remote_system_name']); ?>"/>
    :
    <input id="inbox_port" maxlength="5" name="inbox_port" placeholder="Port" type="text"
           value="<?php p($_['inbox_port']); ?>"/>
    <label for="inbox_ssl"><?php p($l->t('SSL')); ?></label>
    <input <?php if ($_['inbox_ssl']): ?>checked="checked"<?php endif; ?> id="inbox_ssl" name="inbox_ssl" type="checkbox" value="1"/>
    <label for="inbox_username"><?php p($l->t('Username')); ?></label>
    <input id="inbox_username" name="inbox_username" type="text" value="<?php p($_['inbox_username']); ?>"/>
    <label for="inbox_password"><?php p($l->t('Password')); ?></label>
    <input id="inbox_password" name="inbox_password" type="password" value="<?php p($_['inbox_password']); ?>"/>
    <input type="submit" value="<?php p($l->t('Save')); ?>"/>
</form>