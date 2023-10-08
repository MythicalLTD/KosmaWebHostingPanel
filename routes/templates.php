<?php 

$router->add('/kosmapanel/templates/default-website', function () {
    require("../templates/website/default.html");
});

$router->add('/kosmapanel/templates/emails/verify', function () {
    require("../views/notifs/verfiy.html");
});

$router->add('/errors/critical', function () {
    require("../templates/errors/critical.php");
});

$router->add('/errors/404', function () {
    require("../templates/errors/404.html");
});

?>