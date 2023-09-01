<?php
use Kosma\Client;

session_start();
$csrf = new Kosma\CSRF();
use Symfony\Component\Yaml\Yaml;
use Kosma\Database\SettingsManager;
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
$encryption = $KosmaDB['encryptionkey'];

$prot = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$svhost = $_SERVER['HTTP_HOST'];
$appURL = $prot . '://' . $svhost;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        if ($csrf->validate('login-form')) {
            $ip_address = $clientip->getclientip();
            $cf_turnstile_response = $_POST["cf-turnstile-response"];
            $cf_connecting_ip = $ip_address;
            if ($cloudflare_status == "false") {
                $captcha_success = 1;
            } else {
                $captcha_success = $captcha->validate_captcha($cf_turnstile_response, $cf_connecting_ip, $cloudflare_secret_key);
            }
            if ($captcha_success) {
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                if (!$email == "" || $password == "") {
                    try {
                        $query = "SELECT * FROM users WHERE email = '" . $email . "'";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            if (mysqli_num_rows($result) == 1) {
                                $row = mysqli_fetch_assoc($result);
                                $hashedPassword = $row['password'];
                                $code = $row['verification_code'];
                                if ($code == "") {
                                    if (password_verify($password, $hashedPassword)) {
                                        if ($row['suspended'] == "false") {
                                            $conn->query("UPDATE `users` SET `last_ip` = '" . $kosma_encryption->encrypt($ip_address, $encryption) . "' WHERE `users`.`id` = " . $row['id'] . ";");
                                            $token = $row['usertoken'];
                                            $cookie_name = 'token';
                                            $cookie_value = $token;
                                            setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60), '/');
                                            $conn->close();
                                            header('location: /');
                                            die();
                                        } else {
                                            header("location: /auth/login?e=Sorry, but this account is terminated");
                                            $conn->close();
                                            die();
                                        }
                                    } else {
                                        header("location: /auth/login?e=Sorry, but the password is wrong.");
                                        die();
                                    }
                                } else {
                                    header("location: /auth/login?e=Your account is not verified; please check your emails..");
                                    die();
                                }
                            } else {
                                header("location: /auth/login?e=Sorry, but we can't find this email in the database.");
                                die();
                            }
                        } else {
                            header("location: /auth/login?e=Sorry, but we can't find this email in the database.");
                            die();
                        }
                    } catch (Exception $ex) {
                        header("location: /auth/login?e=Sorry, but we can't log you in at this moment.");
                        die();
                    }
                } else {
                    header("location: /auth/login?e=Please fill in all the required information!");
                    die();
                }
            } else {
                header("location: /auth/login?e=Captcha verification failed; please refresh!");
                die();
            }
        } else {
            header("location: /auth/login?e=CSRF verification failed; please refresh!");
            die();
        }
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
        <?= $name ?> - Login

    </title>

    <link rel="icon" type="image/x-icon" href="<?= $logo ?>" />

    <?php
    include(__DIR__ . '/../requirements/head.php');
    ?>
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-auth.css" />
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>


</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bold">
                                    <?= $name ?>
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 pt-2 text-center">Welcome to
                            <?= $name ?> 👋
                        </h4>
                        <p class="mb-4 text-center">Please sign-in to your account and start the adventure </p>
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
                        <form class="mb-3" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                    <?php
                                    if ($settingsManager->getSetting('enable_smtp') == "true") {
                                        ?>
                                        <a href="/auth/forgot-password">
                                            <small>Forgot Password?</small>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <?php
                            if ($settingsManager->getSetting('enable_turnstile') == "true") {
                                ?>
                                <center>
                                    <div class="cf-turnstile"
                                        data-sitekey="<?= $settingsManager->getSetting('turnstile_sitekey') ?>"></div>
                                </center>
                                &nbsp;
                                <?php
                            }
                            ?>
                            <?= $csrf->input('login-form'); ?>
                            <button class="btn btn-primary d-grid w-100" name="submit" type="submit">Login</button>
                        </form>
                        <?php
                        if ($settingsManager->getSetting('registration') == "true") {
                            ?>
                            <p class="text-center">
                                <span>New on our platform?</span>
                                <a href="/auth/register">
                                    <span>Create an account</span>
                                </a>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>
    <?php
    include(__DIR__ . '/../requirements/footer.php');
    ?>
    <script src="/assets/js/pages-auth.js"></script>

</body>

</html>