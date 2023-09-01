<?php

namespace Kosma;

class Encryption {
    public function encrypt($data, $encryptionKey) {
        $encrypted = '';
        $keyLength = strlen($encryptionKey);

        for ($i = 0; $i < strlen($data); $i++) {
            $keyChar = $encryptionKey[$i % $keyLength];
            $encrypted .= chr((ord($data[$i]) + ord($keyChar)) % 256);
        }

        return base64_encode($encrypted);
    }

    public function decrypt($encryptedData, $encryptionKey) {
        $encryptedData = base64_decode($encryptedData);
        $decrypted = '';
        $keyLength = strlen($encryptionKey);

        for ($i = 0; $i < strlen($encryptedData); $i++) {
            $keyChar = $encryptionKey[$i % $keyLength];
            $decrypted .= chr((ord($encryptedData[$i]) - ord($keyChar) + 256) % 256);
        }

        return $decrypted;
    }
}
?>
