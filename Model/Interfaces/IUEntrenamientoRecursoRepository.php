<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/EntrenamientoRecurso.php';

interface IUEntrenamientoRecursoRepository
{
    public function create(EntrenamientoRecurso $recurso): bool;
    public function delete(int $recursoId): bool;
    public function deleteByEntrenamiento(int $entrenamientoId): bool;
    public function findByEntrenamiento(int $entrenamientoId): array;
}
