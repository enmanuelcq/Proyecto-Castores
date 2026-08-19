<?php
// Model/Interfaces/IIncidenciaRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Incidencia.php';

interface IIncidenciaRepository
{
    public function create(Incidencia $incidencia): bool;
    public function update(Incidencia $incidencia): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Incidencia;
    public function findByPartido(int $partidoId): array;
    public function deleteByPartido(int $partidoId): bool;
}