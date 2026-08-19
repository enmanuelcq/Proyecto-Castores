<?php
// Controller/EliminarNoticiaController.php

ob_start();
session_start();

$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryNoticia.php';

// Verificar autenticación
$usuario_id = $_SESSION['usuario']['id'] ?? null;
if (!$usuario_id) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes iniciar sesión.'));
    exit;
}

// Obtener ID
$noticia_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($noticia_id <= 0) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode('ID inválido.'));
    exit;
}

try {
    $repoNoticia = new RepositoryNoticia();
    $noticia = $repoNoticia->findById($noticia_id);
    
    if (!$noticia) {
        throw new Exception("Noticia no encontrada.");
    }
    
    // Eliminar físicamente
    if ($repoNoticia->delete($noticia_id)) {
        // Opcional: eliminar imagen del servidor
        if ($noticia->getImagen()) {
            $rutaImagen = __DIR__ . '/../' . $noticia->getImagen();
            if (file_exists($rutaImagen)) unlink($rutaImagen);
        }
        ob_end_clean();
        header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Noticia eliminada correctamente.'));
    } else {
        throw new Exception("Error al eliminar la noticia.");
    }
} catch (Exception $e) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
}
exit;