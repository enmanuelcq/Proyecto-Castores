<?php
$root = dirname(__DIR__);
require_once $root . '/Config/Session_start.php';
require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryConvocado.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['id_rol'] != 3 && $_SESSION['usuario']['id_rol'] != 1)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'msg' => 'Sin permisos']);
    exit;
}

$action = $_POST['action'] ?? '';
$partidoId = isset($_POST['partido_id']) ? (int) $_POST['partido_id'] : 0;
$atletaId = $_POST['atleta_id'] ?? '';

if (!$partidoId || empty($atletaId)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Faltan datos']);
    exit;
}

$repo = new RepositoryConvocado();
$repoAtleta = new RepositoryAtleta();
$repoCategoria = new RepositoryCategoria();

if ($action === 'convocar') {
    $atleta = $repoAtleta->findById($atletaId);
    if (!$atleta) {
        echo json_encode(['ok' => false, 'msg' => 'Atleta no encontrado']);
        exit;
    }

    require_once $root . '/Model/Repository/RepositoryPartido.php';
    $repoPartido = new RepositoryPartido();
    $partido = $repoPartido->findById($partidoId);
    if (!$partido) {
        echo json_encode(['ok' => false, 'msg' => 'Partido no encontrado']);
        exit;
    }

    $categoriaPartido = (int) $partido->getCategoriaId();
    $catAtleta = null;

    $db = Database::getConnection();
    $stmt2 = $db->prepare("SELECT categoria_id FROM categoria_atleta WHERE Atleta_id = ? ORDER BY anio_id DESC LIMIT 1");
    $stmt2->execute([$atletaId]);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Atleta sin categoría asignada']);
        exit;
    }
    $catAtleta = (int) $row['categoria_id'];

    if ($catAtleta > $categoriaPartido || $catAtleta < $categoriaPartido - 2) {
        echo json_encode(['ok' => false, 'msg' => 'Solo puedes convocar jugadores de la misma categoría o las 2 inferiores']);
        exit;
    }

    $ok = $repo->convocar($partidoId, $atletaId);
    if ($ok) {
        echo json_encode(['ok' => true, 'msg' => 'Jugador convocado']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al convocar']);
    }

} elseif ($action === 'desconvocar') {
    $ok = $repo->desconvocar($partidoId, $atletaId);
    if ($ok) {
        echo json_encode(['ok' => true, 'msg' => 'Jugador removido']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al remover']);
    }

} else {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Acción no válida']);
}
