<?php
// Controller/ActualizarMontosCuotasController.php

$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';

// ============================================================
// 1. INICIALIZAR
// ============================================================
$repoConfig = new RepositoryConfiguracion();
$repoCuota = new RepositoryCuotaMensual();
$db = Database::getConnection();

// ============================================================
// 2. OBTENER DATOS DE CONFIGURACIÓN
// ============================================================
$montoUSD = $repoConfig->getMontoMensualUSD();
$tasa = $repoConfig->getTasaBCV();
$montoNuevo = $montoUSD * $tasa;

// ============================================================
// 3. VALIDAR
// ============================================================
if ($montoNuevo <= 0) {
    $error = "No se puede actualizar: Monto inválido (USD: $montoUSD, Tasa: $tasa)";
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ConfiguracionCuotas') !== false) {
        header('Location: ../index.php?View=ConfiguracionCuotas&error=' . urlencode($error));
    } else {
        // Si se ejecuta desde cron o desde otro lugar, mostrar en pantalla
        echo "❌ " . $error . "\n";
        exit;
    }
    exit;
}

// ============================================================
// 4. ACTUALIZAR CUOTAS PENDIENTES Y PARCIALES
// ============================================================
$sql = "UPDATE cuota_mensual 
        SET monto_establecido = ? 
        WHERE estatus_cuota IN ('Pendiente', 'Parcial')";

$stmt = $db->prepare($sql);
$result = $stmt->execute([$montoNuevo]);

// ============================================================
// 5. GUARDAR FECHA DE ACTUALIZACIÓN
// ============================================================
if ($result) {
    $actualizadas = $stmt->rowCount();
    $repoConfig->setValor('ultima_actualizacion_tasa', date('Y-m-d H:i:s'));
    $mensaje = "✅ Cuotas actualizadas: $actualizadas - Monto: " . number_format($montoNuevo, 2) . " Bs";
    
    // Si viene desde la web (con referer), redirigir
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ConfiguracionCuotas') !== false) {
        header('Location: ../index.php?View=ConfiguracionCuotas&success=1&mensaje=' . urlencode($mensaje));
    } else {
        // Si se ejecuta desde cron o línea de comandos
        echo $mensaje . "\n";
        echo "📅 Fecha: " . date('Y-m-d H:i:s') . "\n";
    }
    exit;
    
} else {
    $error = "❌ Error al actualizar las cuotas";
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ConfiguracionCuotas') !== false) {
        header('Location: ../index.php?View=ConfiguracionCuotas&error=' . urlencode($error));
    } else {
        echo $error . "\n";
        print_r($stmt->errorInfo());
    }
    exit;
}