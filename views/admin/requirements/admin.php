<?php 
if ($sessionManager->getUserInfo('role') == "Administrator") {
       
} else {
    header('location: /404');
    die();
}
?>