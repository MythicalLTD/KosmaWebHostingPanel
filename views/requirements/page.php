<?php 
use Kosma\User\SessionManager;
use Kosma\SettingsManager;
use Kosma\Database\Connect;
use Kosma\Encryption;
use Symfony\Component\Yaml\Yaml;
$conn = new Connect();
$conn = $conn->connectToDatabase();
$settingsManager = new SettingsManager();
$sessionManager = new SessionManager();
$sessionManager->authenticateUser();
$kosma_encryption = new Encryption();
$KosmaConfig = Yaml::parseFile('../config.yml');
$KosmaDB = $KosmaConfig['app'];
$kosma_encryption_key = $KosmaDB['encryptionkey'];
$logo = $settingsManager->getSetting('logo');
$name = $settingsManager->getSetting('name');
?>