<?php
// Controller/PartidosController.php

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryPartido.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryEquipoRival.php';
require_once $root . '/Model/Repository/RepositoryCampo.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryTarjeta.php';
require_once $root . '/Model/Repository/RepositoryConvocado.php';

// ============================================================
// 1. INSTANCIAR REPOSITORIOS
// ============================================================
$repoPartido = new RepositoryPartido();
$repoCategoria = new RepositoryCategoria();
$repoEquipoRival = new RepositoryEquipoRival();
$repoCampo = new RepositoryCampo();
$repoAtleta = new RepositoryAtleta();
$repoTarjeta = new RepositoryTarjeta();
$repoConvocado = new RepositoryConvocado();

// ============================================================
// 2. PAGINACIÓN DE PARTIDOS
// ============================================================
$partidosPorPagina = 5;
$paginaPartidos = isset($_GET['pagina_partidos']) ? (int)$_GET['pagina_partidos'] : 1;
if ($paginaPartidos < 1) $paginaPartidos = 1;

$totalPartidos = $repoPartido->countAll();
$totalPaginasPartidos = ceil($totalPartidos / $partidosPorPagina);
if ($paginaPartidos > $totalPaginasPartidos && $totalPaginasPartidos > 0) {
    $paginaPartidos = $totalPaginasPartidos;
}
$offsetPartidos = ($paginaPartidos - 1) * $partidosPorPagina;

// ============================================================
// 3. OBTENER PARTIDOS CON DETALLES
// ============================================================
$partidos = $repoPartido->findAllWithDetails($partidosPorPagina, $offsetPartidos);

// ============================================================
// 4. DATOS PARA SELECTS DE REGISTRO/EDICIÓN
// ============================================================
$categorias = $repoCategoria->findAll();
$equiposRivales = $repoEquipoRival->findAll();
$campos = $repoCampo->findAll();

// ============================================================
// 5. DATOS PARA EL MODAL DE CARGAR RESULTADO (por partido)
// ============================================================
// Estos datos se cargarán por separado en la vista, dentro del foreach de partidos
// pero aquí dejamos preparados los repositorios.

// ============================================================
// 6. VARIABLES DE PAGINACIÓN
// ============================================================
$paginacionPartidos = [
    'pagina_actual' => $paginaPartidos,
    'total_paginas' => $totalPaginasPartidos,
    'total_registros' => $totalPartidos,
    'registros_por_pagina' => $partidosPorPagina,
    'offset' => $offsetPartidos
];

