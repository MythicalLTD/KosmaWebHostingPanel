<?php
use Kosma\Client;
use PHPMailer\PHPMailer\PHPMailer;

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
$ekey = $KosmaDB['encryptionkey'];

$prot = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$svhost = $_SERVER['HTTP_HOST'];
$appURL = $prot . '://' . $svhost;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        if ($csrf->validate('forgot-password-form')) {
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
                if (!$email == "") {
                    $userdbdv = $conn->query("SELECT * FROM users WHERE email = '" . $email . "'")->fetch_array();
                    $template = file_get_contents('../views/notifs/reset-password.html');
                    $skey = $keygen->generate_keynoinfo();

                    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%ip%', '%app_url%', '%from_email%', '%to_email%', '%first_name%', '%last_name%', '%rkey%');
                    $values = array($logo, $name, $ip_address, $appURL, $settingsManager->getSetting('fromEmail'), $email, $kosma_encryption->decrypt($userdbdv['first_name'], $ekey), $kosma_encryption->decrypt($userdbdv['last_name'], $ekey), $skey);
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
                        $mail->Subject = 'Reset your password for your ' . $name . ' account!';
                        $mail->Body = $emailContent;
                        $mail->send();
                    } catch (Exception $e) {
                        header("location: /auth/forgot-password?e=We are sorry but our SMTP server is down: ");
                        die();
                    }
                    if ($userDB->resetPassword($email,$userdbdv['usertoken'],$skey,$ip_address)) {
                        echo '<script>window.location.href = "' . $appURL . '/auth/login?s=We sent you a password reset email. Please check your emails.";</script>';
                        die();
                    } else {
                        echo '<script>window.location.href = "' . $appURL . '/auth/register?e=We are sorry but we can`t send you a email due an unexpected error";</script>';
                        die();
                    }
                } else {
                    header("location: /auth/forgot-password?e=Please fill in all the required information!");
                    die();
                }
            } else {
                header("location: /auth/forgot-password?e=Captcha verification failed; please refresh!");
                die();
            }
        } else {
            header("location: /auth/forgot-password?e=CSRF verification failed; please refresh!");
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
        <?= $name ?> - Forgot Password
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
                        <h4 class="mb-1 pt-2 text-center">Forgot Password? 🔒</h4>
                        <p class="mb-4 text-center">Enter your email and we'll send you instructions to reset your
                            password</p>
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
                                    placeholder="Enter your email" autofocus />
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
                            <?= $csrf->input('forgot-password-form'); ?>
                            <button type="submit" name="submit" value="yes" class="btn btn-primary d-grid w-100">Send
                                Reset Link</button>
                        </form>
                        <div class="text-center">
                            <a href="/auth/login" class="d-flex align-items-center justify-content-center">
                                <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include(__DIR__ . '/../requirements/footer.php');
    ?>
    <script src="/assets/js/pages-auth.js"></script>
</body>

</html>