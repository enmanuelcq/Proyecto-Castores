<?php
// Controller/EditarNoticiaController.php

ob_start();
session_start();

$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryNoticia.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Método no permitido'));
    exit;
}

// Verificar autenticación
$usuario_id = $_SESSION['usuario']['id'] ?? null;
if (!$usuario_id) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes iniciar sesión.'));
    exit;
}

// Recoger datos
$noticia_id = (int)($_POST['noticia_id'] ?? 0);
$titulo = limpiar_cadena($_POST['titulo'] ?? '');
$contenido = limpiar_cadena($_POST['contenido'] ?? '');
$estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;
$destacada = isset($_POST['destacada']) ? 1 : 0;

// Validar
$errores = [];
if ($noticia_id <= 0) $errores[] = "ID de noticia inválido.";
if (empty($titulo)) $errores[] = "El título es obligatorio.";
if (empty($contenido)) $errores[] = "El contenido es obligatorio.";

if (!empty($errores)) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode(implode(' | ', $errores)));
    exit;
}

// Procesar imagen (opcional)
$imagen = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../Public/Uploads/noticias/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    $nombreUnico = time() . '_' . uniqid() . '.' . $extension;
    $rutaCompleta = $uploadDir . $nombreUnico;
    
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
        $imagen = 'Public/Uploads/noticias/' . $nombreUnico;
    }
}

try {
    $repoNoticia = new RepositoryNoticia();
    $noticia = $repoNoticia->findById($noticia_id);
    
    if (!$noticia) {
        throw new Exception("Noticia no encontrada.");
    }
    
    // Actualizar datos
    $noticia->setTitulo($titulo);
    $noticia->setContenido($contenido);
    if ($imagen) $noticia->setImagen($imagen);
    $noticia->setEstado($estado);
    $noticia->setEsDestacada($destacada);
    
    if ($repoNoticia->update($noticia)) {
        ob_end_clean();
        header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Noticia actualizada correctamente.'));
    } else {
        throw new Exception("Error al actualizar la noticia.");
    }
} catch (Exception $e) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
}
exit;