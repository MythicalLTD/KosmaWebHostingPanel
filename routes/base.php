<?php
// Routes for base
$router->add('/home', function () {
    require("../views/index.php");
});

$router->add('/', function() {
    if (isset($_GET['e'])) {
        header('location: /home?e='. $_GET['e']);
    } else if (isset($_GET['s'])) {
        header('location: /home?s='. $_GET['s']);
    } else {
        header('location: /home');
    }
});
?>