<?php
// Model/Interfaces/IPartidoRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Partido.php';

interface IPartidoRepository
{
    // CRUD
    public function create(Partido $partido): bool;
    public function update(Partido $partido): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Partido;

    // Listados
    public function findAll(): array;
    public function findAllWithDetails(?int $limit = null, ?int $offset = null): array;
    public function findByCategoria(int $categoriaId): array;
    public function findByEstado(string $estado): array;
    public function countAll(): int;

    // Estadísticas
    public function getGoleadoresByPartido(int $partidoId): array;
    public function getTarjetasByPartido(int $partidoId): array;
    public function getCambiosByPartido(int $partidoId): array;
    public function getIncidenciasByPartido(int $partidoId): array;
}