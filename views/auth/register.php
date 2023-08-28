<?php
use Kosma\CSRF;
session_start();
$csrf = new Kosma\CSRF();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Kosma\Database\Connect;
use Kosma\Database\SettingsManager;

function validate_captcha($cf_turnstile_response, $cf_connecting_ip, $cf_secret_key)
{
    $data = array(
        "secret" => $cf_secret_key,
        "response" => $cf_turnstile_response,
        "remoteip" => $cf_connecting_ip
    );

    $url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";

    $options = array(
        "http" => array(
            "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result == false) {
        return false;
    }

    $result = json_decode($result, true);

    return $result["success"];
}

$settingsManager = new SettingsManager();
$logo = $settingsManager->getSetting('logo');
$name = $settingsManager->getSetting('name');

$prot = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$svhost = $_SERVER['HTTP_HOST'];
$appURL = $prot . '://' . $svhost;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        if ($csrf->validate('register-form')) {
            $ip_address = getclientip();
            $cf_turnstile_response = $_POST["cf-turnstile-response"];
            $cf_connecting_ip = $ip_address;
            $captcha_success = validate_captcha($cf_turnstile_response, $cf_connecting_ip, $_CONFIG['cf_secret_key']);
            if ($captcha_success) {
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
                $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $upassword = mysqli_real_escape_string($conn, $_POST['password']);
                $password = password_hash($upassword, PASSWORD_BCRYPT);
                $code = mysqli_real_escape_string($conn, md5(rand()));
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
                    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='" . $email . "'")) > 0) {
                        header("location: /auth/register?e=This username is already in the database.");
                        die();
                    }
                    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "'")) > 0) {
                        header("location: /auth/register?e=This email is already in the database.");
                        die();
                    } else {

                        $template = file_get_contents('../notifs/verify.html');
                        $placeholders = array('%CODE%', '%APP_URL%', '%APP_LOGO%%','%FIRST_NAME%', '%LAST_NAME%', '%APP_NAME%');
                        $values = array($code, $appURL, $logo, $first_name, $last_name, $name);
                        $emailContent = str_replace($placeholders, $values, $template);


                        $mail = new PHPMailer(true);
                        try {
                            $mail->SMTPDebug = 0;
                            $mail->isSMTP();
                            $mail->Host = $_CONFIG['smtpHost'];
                            $mail->SMTPAuth = true;
                            $mail->Username = $_CONFIG['smtpUsername'];
                            $mail->Password = $_CONFIG['smtpPassword'];
                            $mail->SMTPSecure = $_CONFIG['smtpSecure'];
                            $mail->Port = $_CONFIG['smtpPort'];

                            //Recipients
                            $mail->setFrom($_CONFIG['smtpFromEmail']);
                            $mail->addAddress($email);
                            $mail->isHTML(true);
                            $mail->Subject = 'Verify your ' . $_CONFIG['app_name'] . ' account!';
                            $mail->Body = $emailContent;

                            $mail->send();
                            $u_token = generate_key($email, $upassword);
                            $conn->query("
                      INSERT INTO `users` (
                          `username`, 
                          `email`, 
                          `first_name`, 
                          `last_name`, 
                          `password`, 
                          `usertoken`, 
                          `first_ip`, 
                          `last_ip`, 
                          `verification_code`
                      ) VALUES (
                          '" . encrypt($username,$ekey) . "', 
                          '" . $email . "',
                          '" . encrypt($first_name,$ekey) . "', 
                          '" . encrypt($last_name,$ekey) . "', 
                          '" . $password . "', 
                          '" . $u_token. "', 
                          '" . encrypt($ip_address ,$ekey). "', 
                          '" . encrypt($ip_address,$ekey) . "', 
                          '" . $code . "'
                      );
                      ");
                            $conn->close();
                            $domain = substr(strrchr($email, "@"), 1);
                            $redirections = array('gmail.com' => 'https://mail.google.com', 'yahoo.com' => 'https://mail.yahoo.com', 'hotmail.com' => 'https://outlook.live.com', 'outlook.com' => "https://outlook.live.com", 'gmx.net' => "https://gmx.net", 'icloud.com' => "https://www.icloud.com/mail", 'me.com' => "https://www.icloud.com/mail", 'mac.com' => "https://www.icloud.com/mail", );
                            if (isset($redirections[$domain])) {
                                //header("location: " . $redirections[$domain]);
                                echo '<script>window.location.href = "' . $appURL . '/auth/login?s=We sent you a verification email. Please check your emails.";</script>';
                                die();
                            } else {
                                echo '<script>window.location.href = "' . $appURL . '/auth/login?s=We sent you a verification email. Please check your emails.";</script>';
                                die();
                            }
                        } catch (Exception $e) {
                            die($error_500);
                        }
                    }
                } else {
                    header("location: /auth/register?e=Please fill in all the required information.");
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

    <title>Register - <?= $name ?></title>

    <link rel="icon" type="image/x-icon" href="<?= $logo ?>" />

    <?php
    include(__DIR__ . '/../requirements/head.php');
    ?>
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-auth.css" />

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
                                <span class="app-brand-text demo text-body fw-bold"><?= $name?></span>
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
                        <form id="formAuthentication" class="mb-3" action="index.html" method="POST">
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

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions"
                                        name="terms" />
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to
                                        <a href="javascript:void(0);">privacy policy & terms</a>
                                    </label>
                                </div>
                            </div>
                            <button class="btn btn-primary d-grid w-100">Sign up</button>
                        </form>

                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href="auth-login-basic.html">
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
</body>

</html>