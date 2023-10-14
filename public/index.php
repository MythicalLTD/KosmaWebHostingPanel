<?php
try {
    if (file_exists('../vendor/autoload.php')) {
        require("../vendor/autoload.php");
        
    } else {
        die('Hello, it looks like you did not run:  "<code>composer install --no-dev --optimize-autoloader</code>". Please run that and refresh the page');
    }
} catch (Exception $e) {
    die('Hello, it looks like our panel does not like some composer packages. Please report this error on our Discord.:  <code>' . $e . '</code>');
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Kosma\ErrorHandler;

if (!is_writable(__DIR__)) {
    ErrorHandler::ShowCritical("We have no access to our panel directory. Open the terminal and run: chown -R www-data:www-data /var/www/KosmaPanel/*");
    die();
}

$router = new \Router\Router();

include(__DIR__ . "/../routes/base.php");
include(__DIR__ . "/../routes/auth.php");
include(__DIR__ . "/../routes/templates.php");
include(__DIR__ . "/../routes/admin.php");
include(__DIR__ . "/../routes/api.php");

$router->add("/(.*)", function () {
    require("../templates/errors/404.html");
});

$router->route();
?>