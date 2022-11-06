<?php

/**
 * Tier 1 openssl encrypt sample (demo)
 * 
 * @author Robin Correa <robin.correa21@gmail.com>
 */

function generateRandomSymmetricKey($length)
{
    $binaryValue = random_bytes($length);
    return base64_encode($binaryValue);
}

function encryptSymmetricKey($symmetricKey)
{
    $pubKeyPath = "pubkey.pem";
    $pubKeyContent = file_get_contents($pubKeyPath);

    $pubKeyInstance = openssl_get_publickey($pubKeyContent);
    $result = openssl_public_encrypt($symmetricKey, $encryptedSymmetricKey, $pubKeyInstance, OPENSSL_PKCS1_OAEP_PADDING);

    return $result ? base64_encode($encryptedSymmetricKey) : false;
}

function encryptDataBySymmetricKey($dataToEncrypt, $symmetricKey)
{
    $cipher = "aes-256-cbc";
    $iv_size = openssl_cipher_iv_length($cipher);
    $iv = str_repeat(chr(0), $iv_size);
    $encryptedData = openssl_encrypt($dataToEncrypt, $cipher, $symmetricKey, 1, $iv);
    return $encryptedData ? base64_encode($encryptedData) : false;
}

$dataToEncrypt = "RobinCorrea";
$randomSymmetricKey = generateRandomSymmetricKey(16);

$presentInArray = [
    'dataToEncrypt' => $dataToEncrypt,
    'symmetricKey' => $randomSymmetricKey,
    'encryptedSymmetricKey' => encryptSymmetricKey($randomSymmetricKey),
    'encryptedData' => encryptDataBySymmetricKey($dataToEncrypt, $randomSymmetricKey),
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($presentInArray);

// $dataToEncrypt = "RobinCorrea";
// $randomSymmetricKey = generateRandomSymmetricKey(16);
// echo "Data to Encrypt: {$dataToEncrypt}<br />";
// echo "Symmetric Key: {$randomSymmetricKey}<br />";

// $encryptedData = encryptDataBySymmetricKey($dataToEncrypt, $randomSymmetricKey);
// echo "Encrypted Data: {$encryptedData}<br />";
// $encryptedSymmetricKey = encryptSymmetricKey($randomSymmetricKey);
// echo "Encrypted Symmetric Key: {$encryptedSymmetricKey}<br />";
