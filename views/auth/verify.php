<?php 
session_start();
$csrf = new Kosma\CSRF();
use Kosma\Database\Connect;

$conn = new Connect();
$conn = $conn->connectToDatabase();

if (isset($_GET['code']) && !$_GET['code'] == "") {
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    $query = "SELECT * FROM users WHERE verification_code = '$code'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $conn->query("UPDATE `users` SET `verification_code` = NULL WHERE `users`.`id` = ".$row['id'].";");
            $conn->close();
            header('location: /auth/login?s=Email verified. You can log in now.');
            die();
        } else {
            header("location: /auth/login?e=We cant find this code in the database");
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
?>