<?php
use Kosma\Client;
use PHPMailer\PHPMailer\PHPMailer;

session_start();
$csrf = new Kosma\CSRF();
use Symfony\Component\Yaml\Yaml;
use Kosma\SettingsManager;
use Kosma\Database\User;
use Kosma\Keygen;
use Kosma\Database\Connect;
use Kosma\CloudFlare\Captcha;
use Kosma\Encryption;

$captcha = new Captcha();
$userDB = new User();
$keygen = new Keygen();
$clientip = new Client();
$conn = new Connect();
$conn = $conn->connectToDatabase();
$kosma_encryption = new Encryption();
$settingsManager = new SettingsManager();
$logo = $settingsManager->getSetting('logo');
$name = $settingsManager->getSetting('name');
$cloudflare_secret_key = $settingsManager->getSetting('turnstile_secretkey');
$cloudflare_site_key = $settingsManager->getSetting('turnstile_sitekey');
$cloudflare_status = $settingsManager->getSetting('enable_turnstile');
$smtp_stauts = $settingsManager->getSetting('enable_smtp');
$KosmaConfig = Yaml::parseFile('../config.yml');
$KosmaDB = $KosmaConfig['app'];
$ekey = $KosmaDB['encryptionkey'];

$prot = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$svhost = $_SERVER['HTTP_HOST'];
$appURL = $prot . '://' . $svhost;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['code'])) {
        if (!$_GET['code'] == "") {
            $code = mysqli_real_escape_string($conn, $_GET['code']);
            $query = "SELECT * FROM resetpasswords WHERE `user-resetkeycode` = '$code'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                try {
                    $ucode = $conn->query("SELECT * FROM resetpasswords WHERE `user-resetkeycode` = '" . $code . "'")->fetch_array();
                } catch (Exception $ex) {
                    header("location: /auth/reset-password?code=" . $code . "&e=We are sorry but we can't get some required info from the database!");
                    die();
                }
                if (isset($_GET['password'])) {
                    if ($csrf->validate('reset-password-form')) {
                        $upassword = mysqli_real_escape_string($conn, $_GET['password']);
                        $password = password_hash($upassword, PASSWORD_BCRYPT);
                        try {
                            $conn->query("UPDATE `users` SET `password` = '" . $password . "' WHERE `users`.`usertoken` = '" . $ucode['user-apikey'] . "';");
                            $conn->query("DELETE FROM resetpasswords WHERE `resetpasswords`.`id` = " . $ucode['id'] . "");
                            $conn->close();
                        } catch (Exception $ex) {
                            header("location: /auth/reset-password?code=" . $code . "&e=We are sorry but we can't update your password!");
                            die();
                        }
                        header('location: /auth/login');
                        die();
                    } else {
                        header('location: /auth/reset-password?code=' . $code . '&e=CSRF verification failed; please refresh!');
                        die();
                    }
                }
            } else {
                header('location: /auth/login');
                die();
            }
        } else {
            header('location: /auth/login');
            die();
        }
    } else {
        header('location: /auth/login');
        die();
    }
}
?>
<!DOCTYPE html>

<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-semi-dark"
    data-assets-path="/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>
        <?= $name ?> - Reset Password

    </title>

    <link rel="icon" type="image/x-icon" href="<?= $logo ?>" />

    <?php
    include(__DIR__ . '/../requirements/head.php');
    ?>
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-auth.css" />
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="mb-1 pt-2">Reset Password 🔒</h4>
                            <p class="mb-4">for <span class="fw-bold">
                                    <?= $ucode['email'] ?>
                                </span></p>
                        </div>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            if (isset($_GET['e'])) {
                                ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <?= $_GET['e'] ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            if (isset($_GET['s'])) {
                                ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <?= $_GET['s'] ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <form method="GET">
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <input type="hidden" id="code" name="code" value="<?= $_GET['code'] ?>">
                            <?= $csrf->input('reset-password-form'); ?>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">Set new password</button>
                            <div class="text-center">
                                <a href="/auth/login">
                                    <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                    Back to login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include(__DIR__ . '/../requirements/footer.php');
    ?>
    <script src="../../assets/js/pages-auth.js"></script>
</body>

</html>