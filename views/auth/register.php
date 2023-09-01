<?php
use Kosma\Client;

session_start();
$csrf = new Kosma\CSRF();
use Symfony\Component\Yaml\Yaml;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Kosma\Database\SettingsManager;
use Kosma\Database\User;
use Kosma\Keygen;
use Kosma\Database\Connect;
use Kosma\CloudFlare\Captcha;

$captcha = new Captcha();
$userDB = new User();
$keygen = new Keygen();
$clientip = new Client();
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
            $ip_address = $clientip->getclientip();
            $cf_turnstile_response = $_POST["cf-turnstile-response"];
            $cf_connecting_ip = $ip_address;
            if ($cloudflare_status == "false") {
                $captcha_success = 1;
            } else {
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
                        header("location: /auth/register?e=This username is already in the database.");
                        die();
                    }
                    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "'")) > 0) {
                        header("location: /auth/register?e=This email is already in the database.");
                        die();
                    } else {

                        if ($smtp_stauts == "true") {
                            $template = file_get_contents('../views/notifs/verfiy.html');
                            $placeholders = array('%CODE%', '%APP_URL%', '%APP_LOGO%%', '%FIRST_NAME%', '%LAST_NAME%', '%APP_NAME%', '%SMTP_FROM%');
                            $values = array($code, $appURL, $logo, $first_name, $last_name, $name,$settingsManager->getSetting('fromEmail'));
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
                        if ($userDB->createUser($username, $email, $first_name, $last_name, $password, $u_token, $ip_address, $ip_address, $code, $encryption)) {
                            echo '<script>window.location.href = "' . $appURL . '/auth/login?s=We sent you a verification email. Please check your emails.";</script>';
                            die();
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

<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-semi-dark"
    data-assets-path="/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>
        <?= $name ?> - Register

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
                        <h4 class="mb-1 pt-2 text-center">Adventure starts here 🚀</h4>
                        <p class="mb-4 text-center">Start creating an account and enjoy the power of web hosting.</p>
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
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
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
                            <?= $csrf->input('register-form'); ?>
                            <button class="btn btn-primary d-grid w-100" name="submit" type="submit">Sign up</button>
                        </form>

                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href="/auth/login">
                                <span>Sign in instead</span>
                            </a>
                        </p>
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