<?php
// Controller/GuardarConfiguracionController.php

$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?View=configuracion_cuotas&error=' . urlencode('Método no permitido'));
    exit;
}

$monto_usd = isset($_POST['monto_usd']) ? (float)$_POST['monto_usd'] : 0;

if ($monto_usd <= 0) {
    header('Location: ../index.php?View=configuracion_cuotas&error=' . urlencode('El monto debe ser mayor a 0'));
    exit;
}

$repoConfig = new RepositoryConfiguracion();

// Guardar el nuevo monto en USD
if ($repoConfig->setValor('monto_mensual_usd', number_format($monto_usd, 2, '.', ''))) {
    // Recuperar la tasa actual
    $tasa = $repoConfig->getTasaBCV();
    
    // Actualizar automáticamente las cuotas con el nuevo monto
    require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
    $repoCuota = new RepositoryCuotaMensual();
    $db = Database::getConnection();
    
    $montoNuevo = $monto_usd * $tasa;
    if ($montoNuevo > 0) {
        $sql = "UPDATE cuota_mensual 
                SET monto_establecido = ? 
                WHERE estatus_cuota IN ('Pendiente', 'Parcial')";
        $stmt = $db->prepare($sql);
        $stmt->execute([$montoNuevo]);
        $actualizadas = $stmt->rowCount();
        
        $repoConfig->setValor('ultima_actualizacion_cuotas', date('Y-m-d H:i:s'));
        $mensaje = "Configuración guardada. Monto en USD: $monto_usd. Cuotas actualizadas: $actualizadas";
    } else {
        $mensaje = "Configuración guardada. Monto en USD: $monto_usd. (Tasa no disponible, cuotas no actualizadas)";
    }
    
    header('Location: ../index.php?View=configuracion_cuotas&mensaje=' . urlencode($mensaje));
} else {
    header('Location: ../index.php?View=configuracion_cuotas&error=' . urlencode('Error al guardar la configuración'));
}
exit;