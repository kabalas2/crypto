<?php
include 'functions.php';

$ourCryptos = getOurCryptoList();
$url = 'https://rest.coinapi.io/v1/assets?filter_asset_id=' . implode(';', $ourCryptos);

# 1
$result = getDataFromApi($url);
$cryptos = [];
foreach ($result as $row) {
    $cryptos[] = [
        'code' => $row['asset_id'],
        'value' => $row['price_usd'],
    ];
}

$text = '';
foreach ($cryptos as $crypto) {
    // #2
    insertDataToDb($crypto, 'crypto_values');
    // #3
    $text .= analiseData($crypto);
}

// #4
$headers = "From: My Site <admin@mysite.org>";
echo $text;
mail('nikaflash@gmail.com', 'Crypto analize', $text, $headers);







