<?php
// ============================================================
// 1. CONFIGURACIÓN INICIAL
// ============================================================
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Atleta.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';

// ============================================================
// 2. DETECTAR AJAX
// ============================================================
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (!$isAjax) {
    echo '<div class="alert alert-danger">Acceso no permitido.</div>';
    exit;
}

// ============================================================
// 3. VERIFICAR MÉTODO POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<div class="alert alert-danger">Método no permitido.</div>';
    exit;
}

// ============================================================
// 4. RECOGER Y VALIDAR DATOS
// ============================================================
$atleta_id = limpiar_cadena($_POST['atleta_id'] ?? '');
$accion = limpiar_cadena($_POST['accion'] ?? '');

if (empty($atleta_id)) {
    echo '<div class="alert alert-danger">ID del atleta no proporcionado.</div>';
    exit;
}

if ($accion !== 'inactivar') {
    echo '<div class="alert alert-danger">Acción no válida.</div>';
    exit;
}

// ============================================================
// 5. INICIAR TRANSACCIÓN Y ACTUALIZAR
// ============================================================
try {
    $db = Database::getConnection();
    $db->beginTransaction();

    $repoAtleta = new RepositoryAtleta();

    // Buscar atleta
    $atleta = $repoAtleta->findById($atleta_id);
    if (!$atleta) {
        throw new Exception("El atleta con ID " . $atleta_id . " no existe.");
    }

    // Cambiar estado a inactivo (0)
    $atleta->setAtletaEstatus(false);
    if (!$repoAtleta->update($atleta)) {
        throw new Exception("Error al inactivar el atleta.");
    }

    // Confirmar transacción
    $db->commit();

    // ============================================================
    // 6. RESPUESTA EXITOSA
    // ============================================================
    $nombre_completo = $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido();
    $mensaje = "El atleta ha sido inactivado exitosamente.";

    echo '<div class="alert alert-success py-3 text-center" role="alert">';
    echo '<i class="bi bi-check-circle-fill fs-4 d-block mb-2"></i>';
    echo '<strong>¡Inactivación exitosa!</strong>';
    echo '<p class="mb-0">' . htmlspecialchars($nombre_completo) . ' ha sido inactivado.</p>';
    echo '<small class="text-muted">La página se recargará automáticamente...</small>';
    echo '</div>';

    exit;

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    // Respuesta de error
    echo '<div class="alert alert-danger py-3 text-center" role="alert">';
    echo '<i class="bi bi-exclamation-triangle-fill fs-4 d-block mb-2"></i>';
    echo '<strong>Error al inactivar:</strong> ' . htmlspecialchars($e->getMessage());
    echo '</div>';
    exit;
}
?>