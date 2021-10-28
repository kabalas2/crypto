<?php
include 'functions.php';

truncateTable('ranges');

foreach ($_POST['crypto_code'] as $key => $element) {
    $cryptoRange = [
        'code' => $element,
        'min' => $_POST['crypto_min'][$key],
        'max' => $_POST['crypto_max'][$key]
    ];

    insertDataToDb($cryptoRange, 'ranges');
}

header('Location: http://127.0.0.1:8000/');


