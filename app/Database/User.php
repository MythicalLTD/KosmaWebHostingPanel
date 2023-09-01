<?php

namespace Kosma\Database;

use Kosma\Database\Connect;


use Symfony\Component\Yaml\Yaml;

$KosmaConfig = Yaml::parseFile('../config.yml');
$KosmaDB = $KosmaConfig['app'];
$ekey = $KosmaDB['encryptionkey'];
use Kosma\Encryption;

$encryption = new Encryption();


class User
{
    private $db;
    private $encryption; // Added private property for Encryption class instance

    public function __construct()
    {
        $connect = new Connect();
        $this->db = $connect->connectToDatabase();
        $this->encryption = new Encryption(); // Initialize Encryption instance
    }

    public function createUser($username, $email, $first_name, $last_name, $password, $u_token, $first_ip, $last_ip, $verification_code, $ekey)
    {
        $query = "INSERT INTO users (username, email, first_name, last_name, password, usertoken, first_ip, last_ip, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $encryptedUsername = $this->encryption->encrypt($username, $ekey);
        $encryptedFirstName = $this->encryption->encrypt($first_name, $ekey);
        $encryptedLastName = $this->encryption->encrypt($last_name, $ekey);
        $encryptedIpAddress = $this->encryption->encrypt($first_ip, $ekey);
        $stmt->bind_param(
            "sssssssss",
            $encryptedUsername,
            $email,
            $encryptedFirstName,
            $encryptedLastName,
            $password,
            $u_token,
            $encryptedIpAddress,
            $encryptedIpAddress,
            $verification_code
        );
        return $stmt->execute();
    }

    public function resetPassword($email, $userkey, $resetcode, $ipv4)
    {
        $query = "INSERT INTO `resetpasswords` (`email`, `user-apikey`, `user-resetkeycode`, `ip_addres`) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ssss",
            $email,
            $userkey,
            $resetcode,
            $ipv4,
        );
        return $stmt->execute();
    }


    public function __destruct()
    {
        $this->db->close();
    }
}