<?php
// Controller/CargarResultadoController.php

ob_start();
session_start();

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryPartido.php';
require_once $root . '/Model/Repository/RepositoryGol.php';
require_once $root . '/Model/Repository/RepositoryPartidoTarjeta.php';
require_once $root . '/Model/Repository/RepositoryIncidencia.php';
require_once $root . '/Model/Repository/RepositoryCambio.php';
require_once $root . '/Model/Entities/Gol.php';
require_once $root . '/Model/Entities/PartidoTarjeta.php';
require_once $root . '/Model/Entities/Incidencia.php';
require_once $root . '/Model/Entities/Cambio.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Método no permitido'));
    exit;
}

$usuario_id = $_SESSION['usuario']['id'] ?? null;
if (!$usuario_id) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes iniciar sesión.'));
    exit;
}

$partido_id = (int) ($_POST['partido_id'] ?? 0);
$goles_favor = (int) ($_POST['goles_favor'] ?? 0);
$goles_contra = (int) ($_POST['goles_contra'] ?? 0);

if ($partido_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('ID de partido inválido.'));
    exit;
}

try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // ============================================================
    // 1. ACTUALIZAR PARTIDO (SOLO CAMPOS QUE CAMBIAN)
    // ============================================================
    $sql = "UPDATE partido SET goles_favor = ?, goles_contra = ?, estado = 'Finalizado' WHERE partido_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$goles_favor, $goles_contra, $partido_id]);

    // ============================================================
    // 2. ELIMINAR DATOS ANTERIORES DEL PARTIDO
    // ============================================================
    $repoGol = new RepositoryGol();
    $repoGol->deleteByPartido($partido_id);
    
    $repoTarjeta = new RepositoryPartidoTarjeta();
    $repoTarjeta->deleteByPartido($partido_id);
    
    $repoIncidencia = new RepositoryIncidencia();
    $repoIncidencia->deleteByPartido($partido_id);
    
    $repoCambio = new RepositoryCambio();
    $repoCambio->deleteByPartido($partido_id);

    // ============================================================
    // 3. INSERTAR GOLES
    // ============================================================
    $goleadores_ids = $_POST['goleador_id'] ?? [];
    $goles_tiempo = $_POST['gol_tiempo'] ?? [];
    $goles_desc = $_POST['gol_desc'] ?? [];

    foreach ($goleadores_ids as $i => $atleta_id) {
        if (!empty($atleta_id)) {
            $tiempo = !empty($goles_tiempo[$i]) ? $goles_tiempo[$i] . ':00' : '00:00';
            $desc = !empty($goles_desc[$i]) ? $goles_desc[$i] : 'Gol';
            $gol = new Gol(
                null,
                1,
                $tiempo,
                $desc,
                $atleta_id,
                $partido_id
            );
            $repoGol->create($gol);
        }
    }

    // ============================================================
    // 4. INSERTAR TARJETAS
    // ============================================================
    $tarjeta_atleta = $_POST['tarjeta_atleta_id'] ?? [];
    $tarjeta_tipo = $_POST['tarjeta_tipo_id'] ?? [];
    $tarjeta_minuto = $_POST['tarjeta_minuto'] ?? [];

    foreach ($tarjeta_atleta as $i => $atleta_id) {
        if (!empty($atleta_id) && !empty($tarjeta_tipo[$i])) {
            $tarjeta = new PartidoTarjeta(
                null,
                $atleta_id,
                $partido_id,
                (int) $tarjeta_tipo[$i]
            );
            $repoTarjeta->create($tarjeta);
        }
    }

    // ============================================================
    // 5. INSERTAR INCIDENCIAS
    // ============================================================
    $incidencia_atleta = $_POST['incidencia_atleta_id'] ?? [];
    $incidencia_desc = $_POST['incidencia_desc'] ?? [];

    foreach ($incidencia_desc as $i => $desc) {
        if (!empty($desc)) {
            $atleta_id = $incidencia_atleta[$i] ?? '';
            $incidencia = new Incidencia(
                null,
                $desc,
                $atleta_id,
                $partido_id
            );
            $repoIncidencia->create($incidencia);
        }
    }

    // ============================================================
    // 6. INSERTAR CAMBIOS
    // ============================================================
    $cambio_entra = $_POST['cambio_entra_id'] ?? [];
    $cambio_sale = $_POST['cambio_sale_id'] ?? [];
    $cambio_minuto = $_POST['cambio_minuto'] ?? [];

    foreach ($cambio_entra as $i => $entra_id) {
        if (!empty($entra_id) && !empty($cambio_sale[$i])) {
            $cambio = new Cambio(
                null,
                '',
                $cambio_minuto[$i] ?? '0',
                'Entra',
                $entra_id,
                $cambio_sale[$i],
                $partido_id
            );
            $repoCambio->create($cambio);
        }
    }

    // ============================================================
    // 7. CONFIRMAR
    // ============================================================
    $db->commit();

    header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Resultado cargado correctamente.'));
    exit;

} catch (Exception $e) {
    if (isset($db)) $db->rollBack();
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
    exit;
}