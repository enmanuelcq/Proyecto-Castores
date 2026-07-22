<?php
// Controller/ValidarReferenciaController.php

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryOperacion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$tipo_pago_id = isset($_POST['tipo_pago_id']) ? (int)$_POST['tipo_pago_id'] : 0;
$banco = trim($_POST['banco'] ?? '');
$referencia = trim($_POST['referencia'] ?? '');

$response = ['existe' => false];

if ($tipo_pago_id > 0 && !empty($banco) && !empty($referencia)) {
    $repoOperacion = new RepositoryOperacion();
    $response['existe'] = $repoOperacion->existsReferencia($tipo_pago_id, $banco, $referencia);
}

header('Content-Type: application/json');
echo json_encode($response);
exit;