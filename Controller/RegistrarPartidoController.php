<?php
// Controller/RegistrarPartidoController.php

ob_start();
session_start();

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Partido.php';
require_once $root . '/Model/Entities/EquipoRival.php';
require_once $root . '/Model/Entities/Campo.php';
require_once $root . '/Model/Repository/RepositoryPartido.php';
require_once $root . '/Model/Repository/RepositoryEquipoRival.php';
require_once $root . '/Model/Repository/RepositoryCampo.php';

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
$categoria_id = (int) ($_POST['categoria_id'] ?? 0);
$fecha_hora = $_POST['fecha_hora'] ?? '';
$duracion = trim($_POST['duracion'] ?? '90 min');
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
// 3. PROCESAR EQUIPO RIVAL (con validación)
// ============================================================
$equipo_id_raw = $_POST['equipo_id'] ?? '';
$nuevo_equipo = trim($_POST['nuevo_equipo'] ?? '');
$equipo_id = 0;

if ($equipo_id_raw === 'nuevo') {
    // Caso: agregar nuevo equipo
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
        } else {
            header('Location: ../index.php?View=Noticias&error=' . urlencode('Error al crear el equipo rival.'));
            exit;
        }
    }
} else {
    // Caso: seleccionar equipo existente
    $equipo_id = (int) $equipo_id_raw;
}

// Validación final: si $equipo_id es 0 o null, error
if ($equipo_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar un equipo rival válido.'));
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
        } else {
            header('Location: ../index.php?View=Noticias&error=' . urlencode('Error al crear el campo.'));
            exit;
        }
    }
} else {
    $campo_id = (int) $campo_id_raw;
}

if ($campo_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar un campo válido.'));
    exit;
}

// ============================================================
// 5. VALIDACIONES FINALES
// ============================================================
if ($categoria_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes seleccionar una categoría.'));
    exit;
}

if (in_array($tipo_partido, ['Torneo', 'Liga', 'Copa']) && empty($nombre_competencia)) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes especificar el nombre de la competencia.'));
    exit;
}

if (empty($duracion)) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes especificar la duración del partido.'));
    exit;
}

// ============================================================
// 6. REGISTRAR PARTIDO
// ============================================================
try {
    $repoPartido = new RepositoryPartido();
    $partido = new Partido(
        null,
        $fechaPartido,
        $duracion,
        'Pendiente',
        $tipo_partido,
        $nombre_competencia ?: null,
        0,
        0,
        $categoria_id,
        $equipo_id,
        $campo_id
    );
    
    if ($repoPartido->create($partido)) {
        header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Partido registrado correctamente.'));
    } else {
        throw new Exception("Error al registrar el partido.");
    }
} catch (Exception $e) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
}
exit;