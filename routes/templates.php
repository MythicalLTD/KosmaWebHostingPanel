<?php 

$router->add('/kosmapanel/templates/default-website', function () {
    require("../templates/website/default.html");
});

$router->add('/kosmapanel/templates/emails/verify', function () {
    require("../views/notifs/verfiy.html");
});

?>