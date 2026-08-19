<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=Pruebas');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=Pruebas&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';

$tipo_prueba = limpiar_cadena($_POST['tipo_prueba'] ?? '');
$fecha = limpiar_cadena($_POST['fecha'] ?? '');
$categoria_id = intval($_POST['categoria_id'] ?? 0);
$atleta_cedula = limpiar_cadena($_POST['atleta_cedula'] ?? '');

if (empty($tipo_prueba) || empty($fecha) || $categoria_id <= 0 || empty($atleta_cedula)) {
    header('Location: ' . $baseUrl . 'index.php?View=Pruebas&error=' . urlencode('Todos los campos obligatorios deben ser completados'));
    exit;
}

header('Location: ' . $baseUrl . 'index.php?View=Pruebas&success=' . urlencode('Prueba registrada correctamente'));
exit;
