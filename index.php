<?php
include 'functions.php';
$ranges = selectDataFromDb('ranges');

?>
<html>
<div class="wrap">
    <form method="post" action="update.php">
        <?php foreach ($ranges as $range): ?>
            <div>
                <input name="crypto_code[]" type="text" value="<?= $range['code'] ?>">
                <input name="crypto_min[]" type="text" value="<?= $range['min'] ?>">
                <input name="crypto_max[]" type="text" value="<?= $range['max'] ?>">
            </div>
        <?php endforeach; ?>
        <div>
            <br>
            <input type="submit" value="update">
        </div>
    </form>
</div>
</html>