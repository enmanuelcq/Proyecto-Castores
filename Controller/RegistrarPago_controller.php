<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';

$atleta_cedula = limpiar_cadena($_POST['atleta_cedula'] ?? '');
$mes_pago = limpiar_cadena($_POST['mes_pago'] ?? '');
$monto = floatval($_POST['monto'] ?? 0);
$tipo_pago = limpiar_cadena($_POST['tipo_pago'] ?? '');
$fecha_pago = limpiar_cadena($_POST['fecha_pago'] ?? '');

if (empty($atleta_cedula) || empty($mes_pago) || $monto <= 0 || empty($tipo_pago) || empty($fecha_pago)) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Datos incompletos'));
    exit;
}

header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&success=' . urlencode('Pago registrado correctamente'));
exit;
