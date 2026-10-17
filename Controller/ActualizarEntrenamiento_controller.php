<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';
require_once $root . '/Model/Entities/Entrenamiento.php';
require_once $root . '/Model/Entities/CategoriaEntrenamiento.php';
require_once $root . '/Model/Entities/EntrenamientoRecurso.php';
require_once $root . '/Model/Repository/RepositoryEntrenamiento.php';
require_once $root . '/Model/Repository/RepositoryCategoriaEntrenamiento.php';
require_once $root . '/Model/Repository/RepositoryEntrenamientoRecurso.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';

// ============================================================
// 1. CAMPOS BÁSICOS
// ============================================================
$id = intval($_POST['id'] ?? 0);
$fecha = limpiar_cadena($_POST['fecha'] ?? '');
$hora_inicio = limpiar_cadena($_POST['hora_inicio'] ?? '');
$hora_fin = limpiar_cadena($_POST['hora_fin'] ?? '');
$tipo = limpiar_cadena($_POST['tipo'] ?? '');
$lugar = limpiar_cadena($_POST['lugar'] ?? '');
$estado = limpiar_cadena($_POST['estado'] ?? 'Programado');
$total_atletas = intval($_POST['total_atletas'] ?? 0);
$descripcion = limpiar_cadena($_POST['descripcion'] ?? '');

$usuario_sesion_cedula = limpiar_cadena($_SESSION['usuario']['usuario_cedula'] ?? '');
$entrenador_cedula = limpiar_cadena($_POST['entrenador_cedula'] ?? $usuario_sesion_cedula);

$categorias = $_POST['categoria'] ?? [];
$categorias_limpias = [];
foreach ((array) $categorias as $cat) {
    $cat_limpia = limpiar_cadena($cat);
    if ($cat_limpia !== '' && !in_array($cat_limpia, $categorias_limpias)) {
        $categorias_limpias[] = $cat_limpia;
    }
}
$categoria_principal = $categorias_limpias[0] ?? '';

// ============================================================
// 2. VALIDACIONES
// ============================================================
if ($id <= 0 || empty($fecha) || empty($hora_inicio) || empty($hora_fin) || empty($categoria_principal) || empty($tipo) || empty($lugar)) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Datos incompletos'));
    exit;
}

$repo = new RepositoryEntrenamiento();
$existente = $repo->findById($id);

if (!$existente) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Entrenamiento no encontrado'));
    exit;
}

// Resolver IDs de categoría
$repo_categorias = new RepositoryCategoria();
$mapa_cat_id = [];
foreach ($repo_categorias->findAll() as $cat) {
    $mapa_cat_id[$cat->getSubCategoria()] = $cat->getId();
}
$categoriaIds = [];
foreach ($categorias_limpias as $cat) {
    if (isset($mapa_cat_id[$cat])) {
        $categoriaIds[] = $mapa_cat_id[$cat];
    }
}

if (empty($categoriaIds)) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Selecciona al menos una categoría válida'));
    exit;
}

$repo_atleta = new RepositoryAtleta();
$max_atletas = $repo_atleta->contarAtletasDistintosPorCategorias($categoriaIds);
if ($total_atletas > $max_atletas) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('El total de atletas (' . $total_atletas . ') no puede superar el total de las categorías seleccionadas (' . $max_atletas . ')'));
    exit;
}

// ============================================================
// 3. ACTUALIZAR ENTRENAMIENTO
// ============================================================
$fecha_completa = $fecha . ' ' . $hora_inicio . ':00';

$entrenamiento = new Entrenamiento(
    $id,
    $fecha_completa,
    $hora_inicio,
    $hora_fin,
    $descripcion ?: 'Entrenamiento de ' . $tipo,
    $existente->getTiempoEstimado(),
    $tipo,
    $categoria_principal,
    $lugar,
    $estado,
    $total_atletas,
    $descripcion,
    $entrenador_cedula
);

if (!$repo->update($entrenamiento)) {
    header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&error=' . urlencode('Error al actualizar el entrenamiento'));
    exit;
}

// ============================================================
// 4. ACTUALIZAR CATEGORÍAS (borrar y reinsertar)
// ============================================================
$repo_cat_ent = new RepositoryCategoriaEntrenamiento();
$repo_cat_ent->deleteByEntrenamiento($id);
foreach ($categoriaIds as $cid) {
    $ce = new CategoriaEntrenamiento(null, $cid, $id);
    $repo_cat_ent->create($ce);
}

// ============================================================
// 5. ACTUALIZAR RECURSOS MULTIMEDIA
// ============================================================
$repo_recurso = new RepositoryEntrenamientoRecurso();
// Eliminar recursos existentes y sus archivos si los hubiera
$recursosViejos = $repo_recurso->findByEntrenamiento($id);
foreach ($recursosViejos as $rv) {
    if ($rv->getArchivo()) {
        $rutaArchivo = $root . '/' . ltrim($rv->getArchivo(), '/');
        if (is_file($rutaArchivo)) {
            @unlink($rutaArchivo);
        }
    }
}
$repo_recurso->deleteByEntrenamiento($id);

// Enlaces
$tipos = $_POST['recursos_tipo'] ?? [];
$enlaces = $_POST['recursos_enlace'] ?? [];
$descripciones = $_POST['recursos_descripcion'] ?? [];
if (is_array($tipos)) {
    foreach ($tipos as $i => $tipo_recurso) {
        $tipo_recurso = limpiar_cadena($tipo_recurso);
        $enlace = limpiar_cadena($enlaces[$i] ?? '');
        $desc = limpiar_cadena($descripciones[$i] ?? '');
        if ($tipo_recurso === 'enlace' && $enlace !== '') {
            $recurso = new EntrenamientoRecurso(null, $id, 'enlace', $enlace, null, $desc);
            $repo_recurso->create($recurso);
        }
    }
}

// Archivos (imágenes / videos)
$uploadBase = $root . '/Public/Uploads/entrenamientos/';
if (!is_dir($uploadBase)) {
    @mkdir($uploadBase, 0777, true);
}
if (!empty($_FILES['recursos_archivo']['name'])) {
    $files = $_FILES['recursos_archivo'];
    $count = is_array($files['name']) ? count($files['name']) : 1;

    for ($i = 0; $i < $count; $i++) {
        $nombre = $files['name'][$i] ?? '';
        $tmp = $files['tmp_name'][$i] ?? '';
        $error = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;

        if ($error !== UPLOAD_ERR_OK || empty($nombre)) {
            continue;
        }

        $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
        $imagenes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $videos = ['mp4', 'webm', 'ogg', 'mov', 'm4v'];

        if (in_array($ext, $imagenes)) {
            $tipo_recurso = 'imagen';
        } elseif (in_array($ext, $videos)) {
            $tipo_recurso = 'video';
        } else {
            continue;
        }

        $nombreUnico = 'ent_' . uniqid() . '.' . $ext;
        $rutaCompleta = $uploadBase . $nombreUnico;

        if (move_uploaded_file($tmp, $rutaCompleta)) {
            $rutaRelativa = 'Public/Uploads/entrenamientos/' . $nombreUnico;
            $desc = limpiar_cadena($descripciones[$i] ?? '');
            $recurso = new EntrenamientoRecurso(null, $id, $tipo_recurso, null, $rutaRelativa, $desc);
            $repo_recurso->create($recurso);
        }
    }
}

header('Location: ' . $baseUrl . 'index.php?View=Entrenamientos&success=' . urlencode('Entrenamiento actualizado correctamente'));
exit;
