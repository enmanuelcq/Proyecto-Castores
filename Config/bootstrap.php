<?php
// Config/bootstrap.php - Punto único de carga de dependencias

define('ROOT_PATH', dirname(__DIR__));

// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Cargar autoloader personalizado
require_once ROOT_PATH . '/Config/autoloader.php';

// Cargar configuración (funciones auxiliares)
require_once ROOT_PATH . '/Config/main.php';
require_once ROOT_PATH . '/Config/Database.php';

// Headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
