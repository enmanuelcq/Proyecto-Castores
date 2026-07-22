<?php
// Controller/ActualizarTasaBCV.php

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';
require_once $root . '/Model/Service/BCVService.php';

$repoConfig = new RepositoryConfiguracion();
$service = new BCVService();

echo "🔄 Obteniendo tasa del BCV...\n";
$tasaAnterior = $repoConfig->getTasaBCV();
$nuevaTasa = $service->obtenerTasa();

if ($nuevaTasa !== null && $nuevaTasa > 0) {
    $repoConfig->setTasaBCV($nuevaTasa);
    echo "✅ Tasa actualizada: " . number_format($nuevaTasa, 2) . " Bs/USD\n";
    echo "📅 Fecha: " . date('Y-m-d H:i:s') . "\n";
    
    // Si cambió, actualizar cuotas
    if ($tasaAnterior != $nuevaTasa) {
        echo "\n🔄 Actualizando montos de cuotas...\n";
        include 'ActualizarMontosCuotas.php';
    }
} else {
    echo "❌ No se pudo obtener la tasa. Manteniendo: " . number_format($tasaAnterior, 2) . "\n";
}