<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Colegio.php';

interface IColegioRepository
{
    public function create(Colegio $colegio): bool;
    public function update(Colegio $colegio): bool;
    public function delete(int $id): bool;

    public function findById(int $id): ?Colegio;
    public function findByNombre(string $nombre): ?Colegio;
    public function findAll(): array;
}