<?php
// Controller/ConsultaAtletas_controller.php

// Incluir repositorios
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryUniforme.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositorySalud.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';
require_once $root . '/Model/Repository/RepositoryAtletaRepresentante.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryCategoriaAtleta.php';
require_once $root . '/Model/Repository/RepositoryColegio.php';
require_once $root . '/Model/Repository/RepositoryGrado.php';
require_once $root . '/Model/Repository/RepositoryHobbie.php';
require_once $root . '/Model/Repository/RepositoryHobbieAtleta.php';

require_once $root . '/Model/Entities/Atleta.php';
require_once $root . '/Model/Entities/Uniforme.php';
require_once $root . '/Model/Entities/Salud.php';
require_once $root . '/Model/Entities/Representante.php';
require_once $root . '/Model/Entities/AtletaRepresentante.php';
require_once $root . '/Model/Entities/Categoria.php';
require_once $root . '/Model/Entities/CategoriaAtleta.php';
require_once $root . '/Model/Entities/Colegio.php';
require_once $root . '/Model/Entities/Grado.php';
require_once $root . '/Model/Entities/Hobbie.php';
require_once $root . '/Model/Entities/HobbieAtleta.php';

// Instanciar repositorios
$repoAtleta = new RepositoryAtleta();
$repoAtletaRep = new RepositoryAtletaRepresentante();
$repoRepresentante = new RepositoryRepresentante();
$repoCategoriaAtleta = new RepositoryCategoriaAtleta();
$repoCategoria = new RepositoryCategoria();
$repoUniforme = new RepositoryUniforme();
$repoSalud = new RepositorySalud();
$repoGrado = new RepositoryGrado();
$repoColegio = new RepositoryColegio();
$repoHobbieAtleta = new RepositoryHobbieAtleta();
$repoHobbie = new RepositoryHobbie();

// Obtener atletas activos
$atletas = $repoAtleta->findAllActive();

$todos_los_atletas = [];

foreach ($atletas as $atleta) {
    // --- Representante ---
    $relaciones = $repoAtletaRep->findByAtleta($atleta->getAtletaId());
    $representante = null;
    if (!empty($relaciones)) {
        $cedulaRep = $relaciones[0]->getCedulaRepresentante();
        $representante = $repoRepresentante->findByCedula($cedulaRep);
    }

    // --- Categoría actual ---
    $categoriaRelaciones = $repoCategoriaAtleta->findByAtleta($atleta->getAtletaId());
    $categoriaNombre = 'Sin categoría';
    $categoriaAnio = '';
    if (!empty($categoriaRelaciones)) {
        $cat = $repoCategoria->findById($categoriaRelaciones[0]->getCategoriaId());
        $categoriaNombre = $cat ? $cat->getSubCategoria() : 'Sin categoría';
        $categoriaAnio = $categoriaRelaciones[0]->getAnio();
    }

    // --- Uniforme ---
    $uniforme = $repoUniforme->findById($atleta->getUniformeId());
    $numeroFranela = $uniforme ? $uniforme->getFranelaNumero() : 'N/A';

    // --- Salud ---
    $salud = $repoSalud->findByAtletaId($atleta->getAtletaId());

    // --- Grado y Colegio ---
    $grados = $repoGrado->findByAtleta($atleta->getAtletaId());
    $grado = !empty($grados) ? $grados[0] : null;
    $colegio = null;
    if ($grado) {
        $colegio = $repoColegio->findById($grado->getColegioId());
    }

    // --- Hobbie ---
    $hobbieRelaciones = $repoHobbieAtleta->findByAtleta($atleta->getAtletaId());
    $hobbie = null;
    $horarioHobbie = '';
    if (!empty($hobbieRelaciones)) {
        $hobbie = $repoHobbie->findById($hobbieRelaciones[0]->getHobbieId());
        $horarioHobbie = $hobbieRelaciones[0]->getHorario();
    }

    // Guardar todo en el array
    $todos_los_atletas[] = [
        'atleta' => $atleta,
        'representante' => $representante,
        'categoria_nombre' => $categoriaNombre,
        'categoria_anio' => $categoriaAnio,
        'numero_franela' => $numeroFranela,
        'uniforme' => $uniforme,
        'salud' => $salud,
        'grado' => $grado,
        'colegio' => $colegio,
        'hobbie' => $hobbie,
        'horario_hobbie' => $horarioHobbie,
    ];
}
