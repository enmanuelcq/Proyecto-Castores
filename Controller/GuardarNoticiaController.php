<?php
// ============================================================
// 1. INICIAR BUFFER DE SALIDA (¡AL PRINCIPIO!)
// ============================================================
ob_start();

// ============================================================
// 2. CONFIGURACIÓN
// ============================================================
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Noticia.php';
require_once $root . '/Model/Repository/RepositoryNoticia.php';

// ============================================================
// 3. VERIFICAR MÉTODO POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Limpiar buffer antes de redirigir
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Método no permitido'));
    exit;
}

// ============================================================
// 4. INICIAR SESIÓN Y VERIFICAR USUARIO
// ============================================================
session_start();
$usuario_id = $_SESSION['usuario']['id'] ?? null;

if (!$usuario_id) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode('Debes iniciar sesión.'));
    exit;
}

// ============================================================
// 5. RECOGER Y SANITIZAR DATOS
// ============================================================
$titulo = limpiar_cadena($_POST['titulo'] ?? '');
$contenido = limpiar_cadena($_POST['contenido'] ?? '');
$categoria = limpiar_cadena($_POST['categoria'] ?? 'General');
$destacada = isset($_POST['destacada']) ? 1 : 0;
$fecha_publicacion = !empty($_POST['fecha_publicacion']) ? $_POST['fecha_publicacion'] : date('Y-m-d H:i:s');
$destacada = isset($_POST['destacada']) ? 1 : 0;

// ============================================================
// 6. VALIDACIONES
// ============================================================
$errores = [];
if (empty($titulo)) $errores[] = "El título es obligatorio.";
if (empty($contenido)) $errores[] = "El contenido es obligatorio.";

if (!empty($errores)) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode(implode(' | ', $errores)));
    exit;
}

// ============================================================
// 7. PROCESAR IMAGEN
// ============================================================
$imagen = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../Public/Uploads/noticias/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    $nombreUnico = time() . '_' . uniqid() . '.' . $extension;
    $rutaCompleta = $uploadDir . $nombreUnico;
    
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
        $imagen = 'Public/Uploads/noticias/' . $nombreUnico;
    } else {
        ob_end_clean();
        header('Location: ../index.php?View=Noticias&error=' . urlencode('Error al subir la imagen.'));
        exit;
    }
}

// ============================================================
// 8. GUARDAR NOTICIA
// ============================================================
try {
    $repoNoticia = new RepositoryNoticia();
    $noticia = new Noticia(
        null,
        $titulo,
        $contenido,
        $imagen,
        $fecha_publicacion,
        $usuario_id,
        1,
        $destacada
    );
    
    if ($repoNoticia->create($noticia)) {
        // Limpiar buffer y redirigir
        ob_end_clean();
        header('Location: ../index.php?View=Noticias&success=1&mensaje=' . urlencode('Noticia publicada correctamente.'));
    } else {
        throw new Exception("Error al guardar la noticia.");
    }
    
} catch (Exception $e) {
    ob_end_clean();
    header('Location: ../index.php?View=Noticias&error=' . urlencode($e->getMessage()));
}
exit;