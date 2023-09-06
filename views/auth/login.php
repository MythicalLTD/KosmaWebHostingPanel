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
<!--
Author: Keenthemes
Product Name: Metronic
Product Version: 8.1.8
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">

<head>
    <base href="/" />
    <title>
        <?= $name ?> - Login
    </title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="icon" type="image/x-icon" href="<?= $logo ?>" />
    <link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) { themeMode = localStorage.getItem("data-bs-theme"); }
                else { themeMode = defaultThemeMode; }
            }
            if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <style>
            body {
                background-image: url('/assets/media/auth/bg10.jpeg');
            }

            [data-bs-theme="dark"] body {
                background-image: url('/assets/media/auth/bg10-dark.jpeg');
            }
        </style>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-lg-row-fluid">
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="/assets/media/auth/agency.png" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="/assets/media/auth/agency-dark.png" alt="" />
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Fast, Efficient and Productive</h1>
                    <div class="text-gray-600 fs-base text-center fw-semibold">With the flying power of KosmaPanel you
                        can host your websites at the fastest speed with our multi node support.
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                            <form class="form w-100" method="POST">
                                <div class="text-center mb-11">
                                    <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Please sign-in to your account.</div>
                                </div>
                                <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                                    if (isset($_GET['e'])) {
                                        ?>
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <?= $_GET['e'] ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
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
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="fv-row mb-8">
                                    <input type="email" placeholder="Email" name="email" autocomplete="off"
                                        class="form-control bg-transparent" required />
                                </div>
                                <div class="fv-row mb-3">
                                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                                        class="form-control bg-transparent" required />
                                </div>
                                <?php
                                if ($settingsManager->getSetting('enable_smtp') == "true") {
                                    ?>
                                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                        <div></div>
                                        <a href="/auth/forgot-password" class="link-primary">Forgot Password ?</a>
                                    </div>
                                    <?php
                                }
                                ?>
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
                                <div class="d-grid mb-10">
                                    <button type="submit" id="kt_sign_in_submit" name="submit" value="true" class="btn btn-primary">
                                        <span class="indicator-label">Sign In</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <?php
                                if ($settingsManager->getSetting('registration') == "true") {
                                    ?>
                                    <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
                                        <a href="/auth/register" class="link-primary">Sign up</a>
                                    </div>
                                <?php } ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>var hostUrl = "/assets/";</script>
    <script src="/assets/plugins/global/plugins.bundle.js"></script>
    <script src="/assets/js/scripts.bundle.js"></script>
    <script src="/assets/js/custom/authentication/sign-in/general.js"></script>
</body>

</html>