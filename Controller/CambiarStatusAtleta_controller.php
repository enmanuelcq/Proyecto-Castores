<?php
// Controller/CambiarEstadoAtleta_controller.php

// ============================================================
// 1. CONFIGURACIÓN INICIAL
// ============================================================
// Definir la ruta base absoluta
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';

// O usar ruta relativa desde la raíz del servidor
$root = dirname(__DIR__); // Sube un nivel desde Controller/

// ============================================================
// 2. INICIAR SESIÓN
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// 3. VERIFICAR MÉTODO POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Método no permitido'));
    exit;
}

// ============================================================
// 4. OBTENER DATOS
// ============================================================
$atleta_id = $_POST['atleta_id'] ?? '';
$accion = $_POST['accion'] ?? '';

// ============================================================
// 5. VALIDAR DATOS
// ============================================================
if (empty($atleta_id) || !in_array($accion, ['desactivar', 'reactivar'])) {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Datos incompletos'));
    exit;
}

// ============================================================
// 6. CONEXIÓN Y REPOSITORIO
// ============================================================
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';

$repo = new RepositoryAtleta();
$atleta = $repo->findById($atleta_id);

if (!$atleta) {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Atleta no encontrado'));
    exit;
}

// ============================================================
// 7. EJECUTAR ACCIÓN
// ============================================================
if ($accion === 'desactivar') {
    $result = $repo->deactivate($atleta_id);
    $mensaje = 'Atleta desactivado correctamente';
} else {
    $result = $repo->activate($atleta_id);
    $mensaje = 'Atleta reactivado correctamente';
}

// ============================================================
// 8. REDIRIGIR CON MENSAJE
// ============================================================
if ($result) {
    $url = $baseUrl . 'index.php?View=Consulta_atletas&success=1&mensaje=' . urlencode($mensaje);
} else {
    $url = $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Error al cambiar el estado del atleta');
}

// Redirigir
header("Location: $url");
exit;
