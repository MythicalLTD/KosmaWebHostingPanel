<?php

// Routes for /admin
$router->add('/admin', function () {
    //require("../view/auth/register.php");
});

$router->add('/admin/nodes', function () {
    require("../views/admin/nodes/list.php");
});

?>