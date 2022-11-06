# OpenSSL Hybrid encryption

Concept of using both Asymmetric and Symmetric encryptions
- Asymmetric encryption to facilitate a Key Exchange.
- Secret key used with Symmetric encryption for bulk data.

## 1. Generate RSA private key:
openssl.exe genrsa -out privkey.pem 2048
## 2. Generate public key from private key in #1:
openssl.exe rsa -in .\privkey.pem -outform PEM -pubout -out pubkey.pem

## 3. Usage (Demo)
```php
# Change the data (any) to be encrypted on tier1-encrypt.php
$dataToEncrypt = "RobinCorrea";
```

### Run the decryption script
php tier2-decrypt.php

## 4. Hybrid concept:
- Robin randomly generates Symmetric secret key.
- Robin encrypts Data using the generated key.
- Robin encrypts Symmetric secret key with Regina's public key.
- Robin passes all the encrypted data to Regina
- Regina decrypts Symmetric key with Regina's private key.
- Regina decrypts the data using the decrypted symmetric key