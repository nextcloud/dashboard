<?php
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