<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
// se borra
    if (isset($_SESSION['usuario'])) {
        echo '<script>console.log("Session started successfully.");</script>';
        echo '<script>console.log("Usuario: ' . $_SESSION['usuario']['username'] . '");</script>';
        echo '<script>console.log("Cedula: ' . $_SESSION['usuario']['id'] . '");</script>';
        echo '<script>console.log("Rol: ' . $_SESSION['usuario']['id_rol'] . '");</script>';
    }