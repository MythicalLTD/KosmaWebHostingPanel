<?php

// Routes for /auth

$router->add('/auth/login', function () {
    require("../views/auth/login.php");
});

$router->add('/auth/register', function () {
    require("../views/auth/register.php");
});

$router->add('/auth/forgot-password', function () {
    require("../views/auth/forgot-password.php");
});

$router->add('/auth/verify', function () {
    require("../views/auth/verify.php");
});

$router->add('/auth/reset-password', function () {
    require("../views/auth/reset-password.php");
});

$router->add('/auth/logout', function () {
    //require("../functions/logout.php");
});

?>