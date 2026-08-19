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

$nombre = limpiar_cadena($_POST['nombre'] ?? '');
$apellido = limpiar_cadena($_POST['apellido'] ?? '');
$email = limpiar_cadena($_POST['email'] ?? '');
$telefono = limpiar_cadena($_POST['telefono'] ?? '');

if (empty($nombre) || empty($apellido) || empty($email)) {
    header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&error=' . urlencode('Nombre, apellido y email son obligatorios'));
    exit;
}

header('Location: ' . $baseUrl . 'index.php?View=MiPerfil&success=' . urlencode('Perfil actualizado correctamente'));
exit;
