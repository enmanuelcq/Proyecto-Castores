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

$entrenamiento_id = intval($_POST['entrenamiento_id'] ?? 0);

if ($entrenamiento_id <= 0) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Entrenamiento no válido'));
    exit;
}

header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&success=' . urlencode('Asistencia registrada correctamente'));
exit;
