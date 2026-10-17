<?php
// Controller/AjaxTotalAtletasController.php
// Devuelve en JSON la cantidad de atletas DISTINTOS entre las categorías seleccionadas.
// Recibe por POST: categoria[] (nombres de sub_categoria, ej. Sub-12)

header('Content-Type: application/json');

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';

$categorias = $_POST['categoria'] ?? [];
$categorias = is_array($categorias) ? $categorias : [$categorias];

$repo_categorias = new RepositoryCategoria();
$mapa = [];
foreach ($repo_categorias->findAll() as $cat) {
    $mapa[$cat->getSubCategoria()] = $cat->getId();
}

$categoriaIds = [];
foreach ($categorias as $cat) {
    $cat = trim((string) $cat);
    if ($cat !== '' && isset($mapa[$cat])) {
        $categoriaIds[] = $mapa[$cat];
    }
}

$max = 0;
if (!empty($categoriaIds)) {
    $repo_atleta = new RepositoryAtleta();
    $max = $repo_atleta->contarAtletasDistintosPorCategorias($categoriaIds);
}

echo json_encode(['max' => $max]);
exit;
