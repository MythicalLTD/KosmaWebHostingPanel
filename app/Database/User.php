<?php

namespace Kosma\Database;

use Kosma\Database\Connect;
use Kosma\Encryption;

use Symfony\Component\Yaml\Yaml;

$KosmaConfig = Yaml::parseFile('../config.yml');
$KosmaDB = $KosmaConfig['app'];
$ekey = $KosmaDB['encryptionkey'];

$encryption = new Encryption();


class User {
    private $db;

    public function __construct() {
        $connect = new Connect();
        $this->db = $connect->connectToDatabase();
    }

    public function createUser($username, $email, $first_name, $last_name, $password, $u_token, $first_ip, $last_ip, $verification_code, $encryption, $ekey) {

        $query = "INSERT INTO users (username, email, first_name, last_name, password, usertoken, first_ip, last_ip, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $encryptedUsername = $encryption->encrypt($username, $ekey);
        $encryptedFirstName = $encryption->encrypt($first_name, $ekey);
        $encryptedLastName = $encryption->encrypt($last_name, $ekey);
        $encryptedIpAddress = $encryption->encrypt($first_ip, $ekey);
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
            $code
        );
        return $stmt->execute();
    }

    
    public function getUser($username) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function deleteUser($email, $u_token) {
        $query = "DELETE FROM users WHERE email = ? AND usertoken = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $u_token);

        return $stmt->execute();
    }

    public function __destruct() {
        $this->db->close();
    }
}
