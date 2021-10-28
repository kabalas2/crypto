<?php
function getCryptoRange()
{
    $rates = selectDataFromDb('ranges');
    $newArray = [];
    foreach ($rates as $rate) {
        $newArray[$rate['code']] = [
            'min' => $rate['min'],
            'max' => $rate['max'],
        ];
    }

    return $newArray;
}

function getOurCryptoList()
{
    $rates = selectDataFromDb('ranges');
    $newArray = [];
    foreach ($rates as $rate) {
        $newArray[] = $rate['code'];
    }

    return $newArray;
}

function getDataFromApi($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-CoinAPI-Key: 9AC0E924-C28E-4CFC-8475-37434CC6A322'
    ));
    $response = curl_exec($ch);
    $json = json_decode($response, true);
    curl_close($ch);
    return $json;
}

function insertDataToDb($data, $table)
{
    $conn = connectToDb();
    $colums = [];
    $values = '(';
    foreach ($data as $key => $element) {
        $colums[] = $key;
        $values .= "'$element',";
    }
    $values = rtrim($values, ',');
    $values .= ')';
    $colums = implode(',', $colums);


    $sql = "INSERT INTO $table ($colums) VALUES $values";

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function connectToDb()
{
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "cryptos2";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function analiseData($crypto)
{
    $ourCryptosRates = getCryptoRange();
    $min = $ourCryptosRates[$crypto['code']]['min'];
    $max = $ourCryptosRates[$crypto['code']]['max'];
    $currentPrice = $crypto['value'];
    if ($currentPrice < $min) {
        return 'Laikas pirkti ' . $crypto['code'] . '<br>';
    } elseif ($currentPrice > $max) {
        return 'Laikas parduoti ' . $crypto['code'] . '<br>';
    } else {
        return 'Nedarom nieko su ' . $crypto['code'] . '<br>';
    }

}

function selectDataFromDb($table, $select = null, $where = null)
{
    $conn = connectToDb();

    if ($select === null) {
        $select = '*';
    }
    $sql = "SELECT $select FROM $table";

    if ($where !== null) {
        $sql .= ' WHERE ' . $where;
    }

    $result = $conn->query($sql);
    $array = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $array[] = $row;
        }
    }

    return $array;
}

function truncateTable($table)
{
    $conn = connectToDb();
    $sql = "TRUNCATE TABLE $table";
    $conn->query($sql);

}