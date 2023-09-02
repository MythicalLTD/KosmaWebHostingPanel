<?php 
use Kosma\User\SessionManager;
use Kosma\Database\SettingsManager;
use Kosma\Encryption;
use Symfony\Component\Yaml\Yaml;

$settingsManager = new SettingsManager();
$sessionManager = new SessionManager();
$sessionManager->authenticateUser();
$kosma_encryption = new Encryption();
$KosmaConfig = Yaml::parseFile('../config.yml');
$KosmaDB = $KosmaConfig['app'];
$Kosma_encryption_key = $KosmaDB['encryptionkey'];
?>