<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Grado.php';

interface IGradoRepository
{
    public function create(Grado $grado): bool;
    public function update(Grado $grado): bool;
    public function delete(int $id): bool;
    public function deleteByAtleta(string $atletaId): bool; // eliminar todos los grados de un atleta

    public function findById(int $id): ?Grado;
    public function findByAtleta(string $atletaId): array;  // todos los grados de un atleta (historial)
    public function findByColegio(int $colegioId): array;
    public function findCurrentByAtleta(string $atletaId): ?Grado; // último/actual grado
}