<?php

namespace Kosma;

class Keygen {

    public function generatePassword($length = 12) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = "";
        
        $charArrayLength = strlen($chars) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, $charArrayLength)];
        }
        
        return $password;
    }

    public function generate_key($email, $password) {
        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);
        $encoded_email = base64_encode($email);
        $encoded_password = password_hash($password, PASSWORD_DEFAULT);
        $generated_password = $this->generatePassword(12);

        $key = "mythicalsystems_" . base64_encode($formatted_timestamp . $encoded_email . $encoded_password . $generated_password);
        return $key;
    }
    
    public function generate_keynoinfo() {
        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);
        $generated_password = $this->generatePassword(12);
        $key = "mythicalsystems_" . base64_encode($formatted_timestamp . $generated_password);
        return $key;
    }
}
?>
