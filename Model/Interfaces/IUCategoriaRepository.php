<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Categoria.php';

interface ICategoriaRepository
{
    // CRUD básico
    public function create(Categoria $categoria): bool;
    public function update(Categoria $categoria): bool;
    public function delete(int $id): bool;

    // Búsquedas individuales
    public function findById(int $id): ?Categoria;
    public function findBySubCategoria(string $subCategoria): ?Categoria;

    // Listados
    public function findAll(): array;

    // Relación con atletas (opcional, pero útil)
    public function getAtletasByCategoria(int $categoriaId): array; // devuelve array de Atleta
}