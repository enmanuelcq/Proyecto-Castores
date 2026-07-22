<?php
// Controller/ObtenerDetalleCuotaController.php

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryPagoCuota.php';

// Configurar cabecera para JSON
header('Content-Type: application/json');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener el ID de la cuota
$cuota_id = isset($_POST['cuota_id']) ? (int)$_POST['cuota_id'] : 0;

if ($cuota_id <= 0) {
    echo json_encode(['error' => 'ID de cuota no válido']);
    exit;
}

try {
    $repoCuota = new RepositoryCuotaMensual();
    $repoPagoCuota = new RepositoryPagoCuota();
    
    $cuota = $repoCuota->findById($cuota_id);
    
    if (!$cuota) {
        echo json_encode(['error' => 'Cuota no encontrada']);
        exit;
    }
    
    // Obtener el total abonado
    $abonado = $repoPagoCuota->sumAbonadoByCuota($cuota_id);
    $saldo = $cuota->getMontoEstablecido() - $abonado;
    
    // Obtener nombre del mes en español
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
        4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
        10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    $mesNombre = $meses[$cuota->getMes()] ?? 'Mes ' . $cuota->getMes();
    
    // Devolver datos en formato JSON
    echo json_encode([
        'success' => true,
        'mes' => $cuota->getMes(),
        'mes_nombre' => $mesNombre,
        'anio' => $cuota->getAnio(),
        'monto' => $saldo, // Mostramos el saldo pendiente
        'monto_total' => $cuota->getMontoEstablecido(),
        'abonado' => $abonado,
        'estado' => $cuota->getEstatusCuota()
    ]);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener los datos de la cuota: ' . $e->getMessage()]);
}