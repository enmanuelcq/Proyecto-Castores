<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/AtletaRepresentante.php';

interface IAtletaRepresentanteRepository
{
    // CRUD básico
    public function create(AtletaRepresentante $relacion): bool;
    public function update(AtletaRepresentante $relacion): bool;
    public function delete(int $id): bool;
    public function deleteByAtletaAndRepresentante(string $atletaId, string $cedulaRepresentante): bool;

    // Búsquedas individuales
    public function findById(int $id): ?AtletaRepresentante;

    // Búsquedas por atleta o representante
    public function findByAtleta(string $atletaId): array;           // todas las relaciones de un atleta
    public function findByRepresentante(string $cedulaRepresentante): array; // todas las relaciones de un representante

    // Obtener entidades completas (opcional)
    public function getAtletasByRepresentante(string $cedulaRepresentante): array; // devuelve array de Atleta
    public function getRepresentantesByAtleta(string $atletaId): array;            // devuelve array de Representante

    // Verificar existencia
    public function exists(string $atletaId, string $cedulaRepresentante): bool;
}
