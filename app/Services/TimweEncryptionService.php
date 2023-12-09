<?php

namespace App\Services;

class TimweEncryptionService
{
    private $key;
    private $cipher = 'AES-256-ECB';

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function encrypt($data)
    {
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        // Use PKCS5Padding
        $encryptedData = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($encryptedData);
    }

    public function decrypt($encryptedData)
    {
        $encryptedData = base64_decode($encryptedData);

        // Use PKCS5Padding
        $decryptedData = openssl_decrypt($encryptedData, $this->cipher, $this->key, OPENSSL_RAW_DATA);

        return $decryptedData;
    }

}
