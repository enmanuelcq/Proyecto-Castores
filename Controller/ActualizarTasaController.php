<?php
// Controller/ActualizarTasaController.php

$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';
require_once $root . '/Model/Service/BCVService.php';

// ============================================================
// 1. INICIALIZAR
// ============================================================
$repoConfig = new RepositoryConfiguracion();
$service = new BCVService();

// ============================================================
// 2. OBTENER TASA ACTUAL (para comparar)
// ============================================================
$tasaAnterior = $repoConfig->getTasaBCV();

// ============================================================
// 3. OBTENER NUEVA TASA DESDE LA API
// ============================================================
$nuevaTasa = $service->obtenerTasa();

// ============================================================
// 4. GUARDAR Y ACTUALIZAR CUOTAS SI CAMBIÓ
// ============================================================
if ($nuevaTasa !== null && $nuevaTasa > 0) {
    $repoConfig->setTasaBCV($nuevaTasa);
    
    // Redirigir con mensaje de éxito
    $mensaje = "Tasa actualizada: " . number_format($nuevaTasa, 2) . " Bs/USD (anterior: " . number_format($tasaAnterior, 2) . ")";
    
    // Si cambió la tasa, actualizar cuotas automáticamente
    if ($tasaAnterior != $nuevaTasa) {
        // Incluir el controlador de actualización de cuotas
        include 'ActualizarMontosCuotasController.php';
        // Nota: este script ya redirige con su propio mensaje
        exit;
    }
    
    // Si no cambió, solo mostrar mensaje
    header('Location: ../index.php?View=configuracion_cuotas&success=1&mensaje=' . urlencode($mensaje));
    exit;
    
} else {
    // No se pudo obtener la tasa
    $error = "No se pudo obtener la tasa del BCV. Manteniendo valor anterior: " . number_format($tasaAnterior, 2);
    header('Location: ../index.php?View=configuracion_cuotas&error=' . urlencode($error));
    exit;
}