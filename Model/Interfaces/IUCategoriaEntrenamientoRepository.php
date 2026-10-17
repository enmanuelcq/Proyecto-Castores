<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/CategoriaEntrenamiento.php';

interface IUCategoriaEntrenamientoRepository
{
    public function create(CategoriaEntrenamiento $categoriaEntrenamiento): bool;
    public function delete(int $convocatoriaId): bool;
    public function deleteByEntrenamiento(int $entrenamientoId): bool;
    public function findByEntrenamiento(int $entrenamientoId): array;
    public function findCategoriaIdsByEntrenamiento(int $entrenamientoId): array;
}
