<?php
/** @var array $_ */
?>

<?php foreach($_ as $key => $value): ?>
var <?php p($key); ?> = <?php print_unescaped(json_encode($value)); ?>;
<?php endforeach; ?>