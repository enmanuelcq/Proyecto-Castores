<?php
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Usuarios.php';
require_once $root . '/Model/Repository/RepositoryUsuario.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$usuario = limpiar_cadena($_POST['usuario'] ?? '');
$exclude_id = limpiar_cadena($_POST['exclude_id'] ?? '');

if (empty($usuario)) {
    echo json_encode(['existe' => false]);
    exit;
}

$repo = new RepositoryUsuario();
$existente = $repo->findByUsername($usuario);

if ($existente) {
    if (!empty($exclude_id) && $existente->getId() === $exclude_id) {
        echo json_encode(['existe' => false]);
    } else {
        echo json_encode([
            'existe' => true,
            'nombre' => $existente->getNombre(),
            'apellido' => $existente->getApellido()
        ]);
    }
} else {
    echo json_encode(['existe' => false]);
}
