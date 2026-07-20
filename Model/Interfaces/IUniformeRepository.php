<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Uniforme.php';

interface IUniformeRepository
{
    // CRUD
    public function create(Uniforme $uniforme): bool;
    public function update(Uniforme $uniforme): bool;
    public function delete(int $id): bool;

    // Búsquedas
    public function findById(int $id): ?Uniforme;
    public function findAll(): array;
    public function findByFranelaNumero(int $numero): array;
    public function findByFranelaTalla(string $talla): array;
    public function findByShortTalla(string $talla): array;
    public function findByZapatosTalla(string $talla): array;
}