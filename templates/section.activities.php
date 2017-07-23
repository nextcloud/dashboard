<?php
/** @var array $_ */
?>
<?php
if($_['show_activity']===1)    {?>
<section class="dashboard-section span6" <?php if($_['show_wide_activity']===1)    {?> style="width: 97.25%"
    <?php
} ?>>
<h1><a data-toggle="myCollapse" data-target="#activities"><?php p($l->t('Recently uploaded files')); ?></a></h1>
<div class="myCollapse in" id="activities">
    <table class="hover">
        <thead>
        <tr>
            <th><?php p($l->t('File')); ?></th>
            <th><?php p($l->t('Uploaded by')); ?></th>
            <th><?php p($l->t('Uploaded at')); ?></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
</section>
<?php
}
?>
