<?php

namespace Kosma\Database;

use Kosma\Database\Connect;

class SettingsManager {
    private $conn;

    public function __construct() {
        $connect = new Connect();
        $this->conn = $connect->connectToDatabase();
    }

    public function getSetting($settingName) {
        $safeSettingName = $this->conn->real_escape_string($settingName);
        
        $query = "SELECT `$safeSettingName` FROM settings LIMIT 1";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$settingName];
        } else {
            return null; // Setting not found
        }
    }

    public function __destruct() {
        $this->conn->close();
    }
}
