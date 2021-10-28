<?php
include 'functions.php';
$data = selectDataFromDb('crypto_values');
echo '<pre>';
print_r($data);

$newArray = [];
$ourCryptos = getOurCryptoList();
foreach ($data as $element) {
    if (!in_array($element['code'], $ourCryptos)) {
        continue;
    }

    if (!isset($newArray[$element['code']]['min'])) {
        $newArray[$element['code']]['min'] = $element['value'];
    } else {
        if ($newArray[$element['code']]['min'] > $element['value']) {
            $newArray[$element['code']]['min'] = $element['value'];
        }
    }
    if (!isset($newArray[$element['code']]['max'])) {
        $newArray[$element['code']]['max'] = $element['value'];
    } else {
        if ($newArray[$element['code']]['max'] < $element['value']) {
            $newArray[$element['code']]['max'] = $element['value'];
        }
    }

}
truncateTable('ranges');
foreach ($newArray as $key => $element){
    $diff = ($element['max'] - $element['min']) * 0.1;
    $range = [
        'code' => $key,
        'min' => $element['min'] + $diff,
        'max' => $element['max'] - $diff,
    ];
    insertDataToDb($range, 'ranges');
}


print_r($newArray);