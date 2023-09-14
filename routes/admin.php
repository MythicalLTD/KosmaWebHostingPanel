<?php

// Routes for /admin
$router->add('/admin', function () {
    //require("../view/auth/register.php");
});

// Routes for /nodes

$router->add('/admin/nodes', function () {
    require("../views/admin/nodes/list.php");
});

$router->add('/admin/nodes/info', function () {
    require("../views/admin/nodes/info.php");
});

$router->add('/admin/nodes/create', function () {
    require("../views/admin/nodes/create.php");
});

$router->add('/admin/nodes/delete', function () {
    require("../views/admin/nodes/delete.php");
});

$router->add('/admin/nodes/edit', function () {
    require("../views/admin/nodes/edit.php");
});

$router->add('/admin/nodes/power/reboot', function () {
    require("../views/admin/nodes/power/reboot.php");
});

$router->add('/admin/nodes/power/shutdown', function () {
    require("../views/admin/nodes/power/shutdown.php");
});

// Routes for /images
$router->add('/admin/images', function () {
    require("../views/admin/images/list.php");
});

// Routes for /users
$router->add('/admin/users', function () {
    require("../views/admin/users/list.php");
});

?>