<?php
// Controller/EliminarPartidoController.php

ob_start();
session_start();

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryPartido.php';

$usuario_id = $_SESSION['usuario']['id'] ?? null;
if (!$usuario_id) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes iniciar sesión.'));
    exit;
}

$partido_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($partido_id <= 0) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode('ID inválido.'));
    exit;
}

try {
    $repoPartido = new RepositoryPartido();
    if ($repoPartido->delete($partido_id)) {
        header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Partido eliminado correctamente.'));
    } else {
        throw new Exception("Error al eliminar el partido.");
    }
} catch (Exception $e) {
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
}
exit;