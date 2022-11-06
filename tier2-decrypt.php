<?php

/**
 * Tier 2 openssl decrypt sample (demo)
 * 
 * @author Robin Correa <robin.correa21@gmail.com>
 */

function decryptSymmetricKey($encryptedSymmetricKey) {
    // Decrypt Symmetric key (encrypted)
    $binEncryptedSymmetricKey = base64_decode($encryptedSymmetricKey);
    $privKeyPath = "privkey.pem";
    $privKeyContent = file_get_contents($privKeyPath);

    $privKeyInstance = openssl_get_privatekey($privKeyContent);
    $decryptSymmetricKeyResult = openssl_private_decrypt($binEncryptedSymmetricKey, $decryptedSymmetricKey, $privKeyInstance, OPENSSL_PKCS1_OAEP_PADDING);

    return $decryptSymmetricKeyResult ? $decryptedSymmetricKey : false;
}

function decryptData($encryptedSymmetricKey, $encryptedData) {

    // Decrypt symmetric key
    $decryptedSymmetricKey = decryptSymmetricKey($encryptedSymmetricKey);

    // Decrypt data (encrypted)
    $binEncryptedData = base64_decode($encryptedData);

    $cipher = "aes-256-cbc";
    $iv_size = openssl_cipher_iv_length($cipher);
    $iv = str_repeat(chr(0), $iv_size);

    $decryptedData = openssl_decrypt($binEncryptedData, $cipher, $decryptedSymmetricKey, 1, $iv);
    return $decryptedData;
}

// Trigger encrypt data via curl to tier1 script
$ch = curl_init("http://localhost/openssl-playground/tier1-encrypt.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curlResult = curl_exec($ch);
curl_close($ch);

// Decrypt data
$decoded = json_decode($curlResult, 1);
$decrypted = decryptData($decoded['encryptedSymmetricKey'], $decoded['encryptedData']);

echo $decrypted;