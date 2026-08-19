<?php
$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryNoticia.php';

$repoNoticia = new RepositoryNoticia();

// ============================================================
// 1. OBTENER NOTICIA DESTACADA (si existe)
// ============================================================
$noticiaDestacada = $repoNoticia->findDestacada();

// ============================================================
// 2. PAGINACIÓN DE NOTICIAS NORMALES
// ============================================================
$noticiasPorPagina = 4;
$paginaNoticias = isset($_GET['pagina_noticias']) ? (int)$_GET['pagina_noticias'] : 1;
if ($paginaNoticias < 1) $paginaNoticias = 1;

// Total de noticias activas que NO son destacadas
$totalNoticias = $repoNoticia->countActiveSinDestacada();
$totalPaginasNoticias = ceil($totalNoticias / $noticiasPorPagina);
if ($paginaNoticias > $totalPaginasNoticias && $totalPaginasNoticias > 0) {
    $paginaNoticias = $totalPaginasNoticias;
}
$offsetNoticias = ($paginaNoticias - 1) * $noticiasPorPagina;

// Obtener noticias normales de la página actual
$noticias = $repoNoticia->findAllActiveWithLimitSinDestacada($noticiasPorPagina, $offsetNoticias);

$paginacionNoticias = [
    'pagina_actual' => $paginaNoticias,
    'total_paginas' => $totalPaginasNoticias,
    'total_registros' => $totalNoticias,
    'registros_por_pagina' => $noticiasPorPagina,
    'offset' => $offsetNoticias
];