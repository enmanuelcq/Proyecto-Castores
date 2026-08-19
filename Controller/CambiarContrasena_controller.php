<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=MiPerfil');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';

$contrasena_actual = $_POST['contrasena_actual'] ?? '';
$contrasena_nueva = $_POST['contrasena_nueva'] ?? '';
$contrasena_confirmar = $_POST['contrasena_confirmar'] ?? '';

if (empty($contrasena_actual) || empty($contrasena_nueva) || empty($contrasena_confirmar)) {
    header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&error=' . urlencode('Todos los campos son obligatorios'));
    exit;
}

if ($contrasena_nueva !== $contrasena_confirmar) {
    header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&error=' . urlencode('Las contraseñas no coinciden'));
    exit;
}

if (strlen($contrasena_nueva) < 8) {
    header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&error=' . urlencode('La contraseña debe tener al menos 8 caracteres'));
    exit;
}

header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&success=' . urlencode('Contraseña cambiada correctamente'));
exit;
