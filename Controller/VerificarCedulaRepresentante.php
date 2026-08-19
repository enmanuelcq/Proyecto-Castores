<?php
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Representante.php';
require_once $root . '/Model/Interfaces/IRepresentanteRepository.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';
require_once $root . '/Model/Repository/RepositoryAtletaRepresentante.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$cedula = limpiar_cadena($_POST['cedula'] ?? '');

if (empty($cedula)) {
    echo json_encode(['existe' => false]);
    exit;
}

$repoRep = new RepositoryRepresentante();
$repoAR = new RepositoryAtletaRepresentante();
$representante = $repoRep->findByCedula($cedula);

if ($representante) {
    $relaciones = $repoAR->findByRepresentante($cedula);
    $atletas = [];
    foreach ($relaciones as $rel) {
        $atletas[] = [
            'atleta_id' => $rel->getAtletaId(),
            'parentesco' => $rel->getParentesco()
        ];
    }
    echo json_encode([
        'existe' => true,
        'nombre' => $representante->getNombre(),
        'apellido' => $representante->getApellido(),
        'correo' => $representante->getCorreo(),
        'telefono' => $representante->getTelefono(),
        'cantidad_atletas' => count($atletas),
        'atletas' => $atletas
    ]);
} else {
    echo json_encode(['existe' => false]);
}
