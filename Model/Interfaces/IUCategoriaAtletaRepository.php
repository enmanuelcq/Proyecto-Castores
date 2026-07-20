<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/CategoriaAtleta.php';

interface ICategoriaAtletaRepository
{
    public function create(CategoriaAtleta $relacion): bool;
    public function update(CategoriaAtleta $relacion): bool;
    public function delete(int $id): bool;
    public function deleteByAtletaAndCategoria(string $atletaId, int $categoriaId): bool;

    public function findById(int $id): ?CategoriaAtleta;
    public function findByAtleta(string $atletaId): array;
    public function findByCategoria(int $categoriaId): array;

    public function exists(string $atletaId, int $categoriaId): bool;
}