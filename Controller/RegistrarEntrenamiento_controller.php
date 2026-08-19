<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';

$fecha = limpiar_cadena($_POST['fecha'] ?? '');
$hora_inicio = limpiar_cadena($_POST['hora_inicio'] ?? '');
$hora_fin = limpiar_cadena($_POST['hora_fin'] ?? '');
$categoria = limpiar_cadena($_POST['categoria'] ?? '');
$tipo = limpiar_cadena($_POST['tipo'] ?? '');
$lugar = limpiar_cadena($_POST['lugar'] ?? '');

if (empty($fecha) || empty($hora_inicio) || empty($hora_fin) || empty($categoria) || empty($tipo) || empty($lugar)) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Todos los campos obligatorios deben ser completados'));
    exit;
}

header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&success=' . urlencode('Entrenamiento registrado correctamente'));
exit;
