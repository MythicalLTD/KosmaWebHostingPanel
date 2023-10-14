<?php

namespace Kosma\User;
use Kosma\Database\Connect;
use Kosma\Encryption;

$encryption = new Encryption();
class SessionManager
{
    private $dbConnection;
    private $encryption; 
    public function __construct()
    {
        // Initialize the database connection
        $dbConnector = new Connect();
        $this->dbConnection = $dbConnector->connectToDatabase();
        $this->encryption = new Encryption(); 
    }

    public function authenticateUser()
    {
        if (isset($_COOKIE['token'])) {
            $session_id = $_COOKIE['token'];
            $query = "SELECT * FROM users WHERE usertoken='" . $session_id . "'";
            $result = mysqli_query($this->dbConnection, $query);

            if (mysqli_num_rows($result) > 0) {
                session_start();
                $userdbd = $this->dbConnection->query("SELECT * FROM users WHERE usertoken='$session_id'")->fetch_array();
                $_SESSION["token"] = $session_id;
                $_SESSION['loggedin'] = true;
            } else {
                $this->redirectToLogin($this->getFullUrl());
            }
        } else {
            $this->redirectToLogin($this->getFullUrl());
        }
    }

    public function getUserInfo($info)
    {
        $session_id = $_COOKIE["token"];
        $safeInfo = $this->dbConnection->real_escape_string($info);
        $query = "SELECT `$safeInfo` FROM users WHERE usertoken='$session_id' LIMIT 1";
        $result = $this->dbConnection->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$info];
        } else {
            return null; // User or data not found
        }
    }

    private function redirectToLogin($fullUrl)
    {
        $this->deleteCookies();
        header('location: /auth/login?r=' . $fullUrl);
        die();
    }

    private function deleteCookies()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
    }
    public function createUser($username, $email, $first_name, $last_name, $password, $u_token, $first_ip, $last_ip, $verification_code, $ekey)
    {
        $query = "INSERT INTO users (username, email, first_name, last_name, password, usertoken, first_ip, last_ip, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbConnection->prepare($query);
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
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bind_param(
            "ssss",
            $email,
            $userkey,
            $resetcode,
            $ipv4,
        );
        return $stmt->execute();
    }

    public function getIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
    private function getFullUrl()
    {
        $fullUrl = "http";
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
            $fullUrl .= "s";
        }
        $fullUrl .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $fullUrl;
    }
}
?>