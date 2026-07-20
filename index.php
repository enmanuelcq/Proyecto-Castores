<?php 
define('ROOT_PATH', __DIR__);
require "./Config/Session_start.php"; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "./Public/Head.php"; ?>
</head>
<body>
    <?php
    
    if(!isset($_GET['View']) || $_GET['View']==""){
        $_GET['View'] = "home";
    }

    if(is_file("./View/".$_GET['View'].".php") && $_GET['View'] != "Login" && $_GET['View'] != "404"){

        include "./Public/filtro.php";
        include "./View/".$_GET['View'].".php";
        include "./Public/Js/script.php";
        include "./Public/Footer.php";

    } elseif ($_GET['View'] == "Login"){
        include "./View/Login.php";
    } else {
       include "./View/404.php";
    }

    ?>
</body>
</html>
