<?php
// Controller/EntrenamientosController.php
// Carga los datos del módulo de Entrenamientos para que la vista solo se encargue de presentar.
// Sigue el patrón de PartidosController.php y ConsultarAtleta_controller.php.

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryEntrenamiento.php';
require_once $root . '/Model/Repository/RepositoryDesempenioEntrenamiento.php';
require_once $root . '/Model/Repository/RepositoryUsuario.php';
require_once $root . '/Model/Repository/RepositoryCategoriaEntrenamiento.php';
require_once $root . '/Model/Repository/RepositoryEntrenamientoRecurso.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';

// ============================================================
// 1. INSTANCIAR REPOSITORIOS
// ============================================================
$repo_entrenamientos = new RepositoryEntrenamiento();
$repo_usuarios = new RepositoryUsuario();
$repo_desempenio = new RepositoryDesempenioEntrenamiento();
$repo_cat_ent = new RepositoryCategoriaEntrenamiento();
$repo_recurso = new RepositoryEntrenamientoRecurso();
$repo_categorias = new RepositoryCategoria();

// ============================================================
// 1b. LISTA DE CATEGORÍAS PARA EL SELECTOR MÚLTIPLE
// ============================================================
$todas_las_categorias = [];
foreach ($repo_categorias->findAll() as $cat) {
    $todas_las_categorias[] = $cat->getSubCategoria();
}

// ============================================================
// 2. OBTENER TODOS LOS ENTRENAMIENTOS (mapeados + entrenador)
// ============================================================
$entrenamientos_crudos = $repo_entrenamientos->findAll();
$todos_los_entrenamientos = [];
foreach ($entrenamientos_crudos as $e) {
    $arr = $e->toArray();
    $usuario = $repo_usuarios->findByCedula($arr['entrenador_cedula']);
    $arr['entrenador_nombre'] = $usuario
        ? ($usuario->getNombre() . ' ' . $usuario->getApellido())
        : 'Sin asignar';
    // Categorías (tabla puente)
    $arr['categorias'] = [];
    foreach ($repo_cat_ent->findByEntrenamiento($e->getEntrenamientoId()) as $ce) {
        if ($ce->getSubCategoria() !== null && $ce->getSubCategoria() !== '') {
            $arr['categorias'][] = $ce->getSubCategoria();
        }
    }
    // Recursos multimedia
    $arr['recursos'] = [];
    foreach ($repo_recurso->findByEntrenamiento($e->getEntrenamientoId()) as $rec) {
        $arr['recursos'][] = $rec->toArray();
    }
    $todos_los_entrenamientos[] = $arr;
}

// ============================================================
// 3. LISTA DE ENTRENADORES (rol_id = 3) para el selector
// ============================================================
$entrenadores = $repo_usuarios->findAllByRol(3);
$lista_entrenadores = [];
foreach ($entrenadores as $ent) {
    $lista_entrenadores[] = [
        'cedula' => $ent->getCedula(),
        'nombre' => $ent->getNombre() . ' ' . $ent->getApellido(),
    ];
}

// ============================================================
// 4. OBTENER TODA LA ASISTENCIA DE LOS ENTRENAMIENTOS
// ============================================================
$asistencia_entrenamientos = [];
if (!empty($todos_los_entrenamientos)) {
    foreach ($todos_los_entrenamientos as $ent) {
        $desempenios = $repo_desempenio->findByEntrenamientoId($ent['id']);
        foreach ($desempenios as $d) {
            $asistencia_entrenamientos[] = $d->toArray();
        }
    }
}
$toda_la_asistencia = $asistencia_entrenamientos;
