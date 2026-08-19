<?php
// Model/Interfaces/INoticiaRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Noticia.php';

interface INoticiaRepository
{
    // CRUD básico
    public function create(Noticia $noticia): bool;
    public function update(Noticia $noticia): bool;
    public function delete(int $id): bool;
    public function deactivate(int $id): bool;
    public function activate(int $id): bool;

    // Búsquedas
    public function findById(int $id): ?Noticia;
    public function findAll(): array;
    public function findAllActive(): array;
    public function findLatest(int $limit = 5): array;
    public function findAllActiveWithLimit(int $limit, int $offset): array;
    public function countActive(): int;
    public function findDestacada(): ?Noticia;
    public function countActiveSinDestacada(): int;
    public function findAllActiveWithLimitSinDestacada(int $limit, int $offset): array;
    public function limpiarDestacadas(): bool;
}