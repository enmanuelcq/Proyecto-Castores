<?php
// Controller/EditarPartidoController.php

ob_start();
session_start();

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryPartido.php';
require_once $root . '/Model/Repository/RepositoryEquipoRival.php';
require_once $root . '/Model/Repository/RepositoryCampo.php';
require_once $root . '/Model/Entities/EquipoRival.php';
require_once $root . '/Model/Entities/Campo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Método no permitido'));
    exit;
}

$usuario_id = $_SESSION['usuario']['id'] ?? null;
if (!$usuario_id) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes iniciar sesión.'));
    exit;
}

// ============================================================
// 1. RECOGER DATOS
// ============================================================
$partido_id = (int) ($_POST['partido_id'] ?? 0);
$categoria_id = (int) ($_POST['categoria_id'] ?? 0);
$fecha_hora = $_POST['fecha_hora'] ?? '';
$duracion = trim($_POST['duracion'] ?? '90 min');
$estado = $_POST['estado'] ?? 'Pendiente';
$tipo_partido = $_POST['tipo_partido'] ?? 'Amistoso';
$nombre_competencia = trim($_POST['nombre_competencia'] ?? '');

// ============================================================
// 2. VALIDAR FECHA/HORA
// ============================================================
if (empty($fecha_hora)) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar fecha y hora.'));
    exit;
}
$timestamp = strtotime($fecha_hora);
if ($timestamp === false || $timestamp === -1) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Formato de fecha/hora inválido.'));
    exit;
}
$fechaPartido = date('Y-m-d H:i:s', $timestamp);
if ($fechaPartido === '1970-01-01 00:00:00') {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Fecha inválida.'));
    exit;
}

// ============================================================
// 3. PROCESAR EQUIPO RIVAL
// ============================================================
$equipo_id_raw = $_POST['equipo_id'] ?? '';
$nuevo_equipo = trim($_POST['nuevo_equipo'] ?? '');
$equipo_id = 0;

if ($equipo_id_raw === 'nuevo') {
    if (empty($nuevo_equipo)) {
        header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes escribir el nombre del nuevo equipo.'));
        exit;
    }
    $repoEquipo = new RepositoryEquipoRival();
    $equipoExistente = $repoEquipo->findByNombre($nuevo_equipo);
    if ($equipoExistente) {
        $equipo_id = $equipoExistente->getId();
    } else {
        $equipo = new EquipoRival(null, $nuevo_equipo);
        if ($repoEquipo->create($equipo)) {
            $equipo_id = $equipo->getId();
            if ($equipo_id <= 0) {
                throw new Exception("Error al obtener el ID del nuevo equipo.");
            }
        } else {
            header('Location: ../index.php?View=Noticias&error=' . urlencode('Error al crear el equipo rival.'));
            exit;
        }
    }
} else {
    $equipo_id = (int) $equipo_id_raw;
}

// ============================================================
// 3.1 VERIFICAR QUE EL EQUIPO EXISTA
// ============================================================
$repoEquipo = new RepositoryEquipoRival();
$equipoVerificar = $repoEquipo->findById($equipo_id);
if (!$equipoVerificar) {
    error_log("ERROR: El equipo ID $equipo_id no existe en equipo_rival.");
    header('Location: ../index.php?View=Noticias&error=' . urlencode('El equipo seleccionado no existe. Por favor, selecciona otro.'));
    exit;
}

// ============================================================
// 4. PROCESAR CAMPO
// ============================================================
$campo_id_raw = $_POST['campo_id'] ?? '';
$nuevo_campo = trim($_POST['nuevo_campo'] ?? '');
$mapa_campo = trim($_POST['mapa_campo'] ?? '');
$campo_id = 0;

if ($campo_id_raw === 'nuevo') {
    if (empty($nuevo_campo)) {
        header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes escribir el nombre del nuevo campo.'));
        exit;
    }
    $repoCampo = new RepositoryCampo();
    $campoExistente = $repoCampo->findByNombre($nuevo_campo);
    if ($campoExistente) {
        $campo_id = $campoExistente->getId();
    } else {
        $campo = new Campo(null, $nuevo_campo, '', $mapa_campo ?: null);
        if ($repoCampo->create($campo)) {
            $campo_id = $campo->getId();
            if ($campo_id <= 0) {
                throw new Exception("Error al obtener el ID del nuevo campo.");
            }
        } else {
            header('Location: ../index.php?View=Noticias&error=' . urlencode('Error al crear el campo.'));
            exit;
        }
    }
} else {
    $campo_id = (int) $campo_id_raw;
}

// ============================================================
// 5. VALIDACIONES FINALES
// ============================================================
if ($partido_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('ID de partido inválido.'));
    exit;
}

if ($categoria_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar una categoría.'));
    exit;
}

if ($equipo_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar un equipo rival válido.'));
    exit;
}

if ($campo_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar un campo válido.'));
    exit;
}

if (in_array($tipo_partido, ['Torneo', 'Liga', 'Copa']) && empty($nombre_competencia)) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes especificar el nombre de la competencia.'));
    exit;
}

if (empty($duracion)) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes especificar la duración.'));
    exit;
}

// ============================================================
// 6. ACTUALIZAR PARTIDO
// ============================================================
try {
    $repoPartido = new RepositoryPartido();
    $partido = $repoPartido->findById($partido_id);
    if (!$partido) {
        throw new Exception("Partido no encontrado.");
    }

    $partido->setFechaPartido($fechaPartido);
    $partido->setDuracion($duracion);
    $partido->setEstado($estado);
    $partido->setCategoriaId($categoria_id);
    $partido->setEquipoId($equipo_id);
    $partido->setCampoId($campo_id);
    $partido->setTipoPartido($tipo_partido);
    $partido->setNombreCompetencia($nombre_competencia ?: null);

    if ($repoPartido->update($partido)) {
        header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Partido actualizado correctamente.'));
    } else {
        throw new Exception("Error al actualizar el partido.");
    }
} catch (Exception $e) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
}
exit;