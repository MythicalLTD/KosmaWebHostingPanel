<?php
session_start();
$csrf = new Kosma\CSRF();
use Symfony\Component\Yaml\Yaml;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Kosma\SettingsManager;
use Kosma\Keygen;
use Kosma\Database\Connect;
use Kosma\CloudFlare\Captcha;
use Kosma\User\SessionManager;

$sessionm = new SessionManager();
$captcha = new Captcha();
$keygen = new Keygen();
$conn = new Connect();
$conn = $conn->connectToDatabase();

$settingsManager = new SettingsManager();
if ($settingsManager->getSetting('registration') == "false") {
    header('location: /auth/login');
    die();
}
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
        if ($csrf->validate('register-form')) {
            $ip_address = $sessionm->getIP();
            $cf_connecting_ip = $ip_address;
            if ($cloudflare_status == "false") {
                $captcha_success = 1;
            } else {
                $cf_turnstile_response = $_POST["cf-turnstile-response"];
                if ($cf_turnstile_response == null) {
                    header('location: /auth/register?e=Captcha verification failed; please refresh!');
                    die();
                }
                $captcha_success = $captcha->validate_captcha($cf_turnstile_response, $cf_connecting_ip, $cloudflare_secret_key);
            }
            if ($captcha_success) {
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
                $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $upassword = mysqli_real_escape_string($conn, $_POST['password']);
                $password = password_hash($upassword, PASSWORD_BCRYPT);
                if ($smtp_stauts == "true") {
                    $code = mysqli_real_escape_string($conn, md5(rand()));
                } else {
                    $code = "";
                }
                if (!$username == "" && !$email == "" && !$first_name == "" && !$last_name == "" && !$upassword == "") {
                    $insecure_passwords = array("password", "1234", "qwerty", "letmein", "admin", "pass", "123456789", "dad", "mom", "kek", "12345");
                    if (in_array($upassword, $insecure_passwords)) {
                        header('location: /auth/register?e=Password is not secure. Please choose a different one');
                        die();
                    }
                    $blocked_usernames = array("password", "1234", "qwerty", "letmein", "admin", "pass", "123456789", "dad", "mom", "kek", "fuck", "pussy", "plexed", "badsk", "username");
                    if (in_array($username, $blocked_usernames)) {
                        header('location: /auth/register?e=It looks like we blocked this username from being used. Please choose another username.');
                        die();
                    }
                    if (preg_match("/[^a-zA-Z]+/", $username)) {
                        header('location: /auth/register?e=Please only use characters from <code>A-Z</code> in your username!');
                        die();
                    }
                    if (preg_match("/[^a-zA-Z]+/", $first_name)) {
                        header('location: /auth/register?e=Please only use characters from <code>A-Z</code> in your first name!');
                        die();
                    }
                    if (preg_match("/[^a-zA-Z]+/", $last_name)) {
                        header('location: /auth/register?e=Please only use characters from <code>A-Z</code> in your last name!');
                        die();
                    }
                    if ($username == $upassword) {
                        header('location: /auth/register?e=Password can`t be the same like the username');
                        die();
                    }
                    if ($email == $upassword) {
                        header('location: /auth/register?e=Password can`t be the same like the email');
                        die();
                    }
                    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='" . $email . "'")) > 0) {
                        header("location: /auth/register?e=This email is already in the database.");
                        die();
                    }
                    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "'")) > 0) {
                        header("location: /auth/register?e=This username is already in the database.");
                        die();
                    } else {

                        if (!$code == "") {
                            $template = file_get_contents('../views/notifs/verfiy.html');
                            $placeholders = array('%CODE%', '%APP_URL%', '%APP_LOGO%%', '%FIRST_NAME%', '%LAST_NAME%', '%APP_NAME%', '%SMTP_FROM%');
                            $values = array($code, $appURL, $logo, $first_name, $last_name, $name, $settingsManager->getSetting('fromEmail'));
                            $emailContent = str_replace($placeholders, $values, $template);
                            $mail = new PHPMailer(true);
                            try {
                                $mail->SMTPDebug = 0;
                                $mail->isSMTP();
                                $mail->Host = $settingsManager->getSetting('smtpHost');
                                $mail->SMTPAuth = true;
                                $mail->Username = $settingsManager->getSetting('smtpUsername');
                                $mail->Password = $settingsManager->getSetting('smtpPassword');
                                $mail->SMTPSecure = $settingsManager->getSetting('smtpSecure');
                                $mail->Port = $settingsManager->getSetting('smtpPort');
                                //Recipients
                                $mail->setFrom($settingsManager->getSetting('fromEmail'));
                                $mail->addAddress($email);
                                $mail->isHTML(true);
                                $mail->Subject = 'Verify your ' . $name . ' account!';
                                $mail->Body = $emailContent;
                                $mail->send();
                            } catch (Exception $e) {
                                header("location: /auth/register?e=We are sorry but our SMTP server is down: ");
                                die();
                            }
                        }
                        $u_token = $keygen->generate_key($email, $upassword);
                        if ($sessionm->createUser($username, $email, $first_name, $last_name, $password, $u_token, $ip_address, $ip_address, $code, $encryption)) {
                            if ($code == "") {
                                echo '<script>window.location.href = "' . $appURL . '/auth/login?s=Welcome to ' . $name . '.";</script>';
                                die();
                            } else {
                                echo '<script>window.location.href = "' . $appURL . '/auth/login?s=We sent you a verification email. Please check your emails.";</script>';
                                die();
                            }
                        } else {
                            echo '<script>window.location.href = "' . $appURL . '/auth/register?e=We are sorry but we can`t add you in our database due an unexpected error";</script>';
                            die();
                        }
                    }
                } else {
                    header("location: /auth/register?e=Please fill in all the required information.3");
                    die();
                }

            } else {
                header("location: /auth/register?e=Captcha verification failed; please refresh!");
                die();
            }
        } else {
            header("location: /auth/register?e=CSRF verification failed; please refresh!");
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
        <?= $name ?> - Register
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
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <style>
            body {
                background-image: url('assets/media/auth/bg10.jpeg');
            }

            [data-bs-theme="dark"] body {
                background-image: url('assets/media/auth/bg10-dark.jpeg');
            }
        </style>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-lg-row-fluid">
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="assets/media/auth/agency.png" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="assets/media/auth/agency-dark.png" alt="" />
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
                                    <h1 class="text-dark fw-bolder mb-3">Sign Up</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Please sign-up for an account.</div>
                                </div>
                                <?php include(__DIR__ . '/../components/alert.php') ?>
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="Enter your first name" autofocus />
                                </div>
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="Enter your last name" autofocus />
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter your username" autofocus />
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" placeholder="Email" name="email" autocomplete="off"
                                        class="form-control bg-transparent" />
                                </div>
                                <div class="fv-row mb-8" data-kt-password-meter="true">
                                    <div class="mb-1">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="position-relative mb-3">
                                            <input class="form-control bg-transparent" type="password"
                                                placeholder="Password" name="password" autocomplete="off" />
                                            <span
                                                class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                data-kt-password-meter-control="visibility">
                                                <i class="ki-outline ki-eye-slash fs-2"></i>
                                                <i class="ki-outline ki-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3"
                                            data-kt-password-meter-control="highlight">
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                            </div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                            </div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                            </div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                        </div>
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
                                <?= $csrf->input('register-form'); ?>
                                <div class="d-grid mb-10">
                                    <button type="submit" id="kt_sign_up_submit" name="submit" class="btn btn-primary">
                                        <span class="indicator-label">Sign up</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
                                    <a href="/auth/login" class="link-primary fw-semibold">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var hostUrl = "/assets/";
        </script>
        <script src="/assets/plugins/global/plugins.bundle.js"></script>
        <script src="/assets/js/scripts.bundle.js"></script>
        <script src="/assets/js/custom/authentication/sign-in/general.js"></script>
</body>

</html>