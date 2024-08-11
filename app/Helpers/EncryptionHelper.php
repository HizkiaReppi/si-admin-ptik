<?php

namespace App\Helpers;

class EncryptionHelper
{
    /**
     * Encrypts a given content using AES-256-CBC encryption algorithm.
     *
     * @param string $content The content to be encrypted.
     * @param string $key The encryption key.
     * @return string The encrypted content.
     */
    public static function encryptContent($content, $key)
    {
        return openssl_encrypt($content, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key, true), 0, 16));
    }

    /**
     * Encrypts a given content using AES-256-CBC encryption algorithm.
     *
     * @param string $encryptedContent The content to be decrypted.
     * @param string $key The decryption key.
     * @return string The decrypted content.
     */
    public static function decryptContent($encryptedContent, $key)
    {
        return openssl_decrypt($encryptedContent, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key, true), 0, 16));
    }
}
