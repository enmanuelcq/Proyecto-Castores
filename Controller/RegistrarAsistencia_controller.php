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
require_once $root . '/Model/Repository/RepositoryDesempenioEntrenamiento.php';

$entrenamiento_id = intval($_POST['entrenamiento_id'] ?? 0);

if ($entrenamiento_id <= 0) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Entrenamiento no válido'));
    exit;
}

$repo = new RepositoryDesempenioEntrenamiento();

$errores = 0;

if (isset($_POST['asistencia']) && is_array($_POST['asistencia'])) {
    foreach ($_POST['asistencia'] as $atleta_id => $asistio) {
        $atleta_id_limpio = limpiar_cadena($atleta_id);
        $asistio_val = ($asistio == 'on' || $asistio == '1') ? 1 : 0;
        $observacion = limpiar_cadena($_POST['observacion'][$atleta_id] ?? '');

        if (!$repo->upsert($entrenamiento_id, $atleta_id_limpio, $asistio_val, $observacion)) {
            $errores++;
        }
    }
}

if ($errores === 0) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&success=' . urlencode('Asistencia registrada correctamente'));
} else {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Error al registrar asistencia de ' . $errores . ' atleta(s)'));
}
exit;
