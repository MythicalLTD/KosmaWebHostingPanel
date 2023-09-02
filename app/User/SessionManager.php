<?php

namespace Kosma\User;


use Kosma\Database\Connect;

class SessionManager
{
    private $dbConnection;

    public function __construct()
    {
        // Initialize the database connection
        $dbConnector = new Connect();
        $this->dbConnection = $dbConnector->connectToDatabase();
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