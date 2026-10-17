<?php
// Controller/ValidarCedulaRepresentante_controller.php
// Valida si una cédula existe en la tabla representante

header('Content-Type: application/json');

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';

// Obtener cédula del POST o GET
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');

if (empty($cedula)) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'La cédula es requerida'
    ]);
    exit;
}

// Limpiar la cédula (espacios y guiones)
$cedula = preg_replace('/[\s\-]/', '', $cedula);

$repo = new RepositoryRepresentante();
$representante = $repo->findByCedula($cedula);

if ($representante) {
    echo json_encode([
        'success' => true,
        'mensaje' => 'Representante encontrado',
        'representante' => [
            'cedula' => $representante->getCedula(),
            'nombre' => $representante->getNombre(),
            'apellido' => $representante->getApellido(),
            'correo' => $representante->getCorreo(),
            'telefono' => $representante->getTelefono(),
            'ocupacion' => $representante->getOcupacion()
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'mensaje' => 'La cédula no se encuentra registrada en el sistema. Contacte a la administración.'
    ]);
}
exit;
