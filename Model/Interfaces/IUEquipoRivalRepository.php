<?php
// Model/Interfaces/IEquipoRivalRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/EquipoRival.php';

interface IEquipoRivalRepository
{
    public function create(EquipoRival $equipo): bool;
    public function update(EquipoRival $equipo): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?EquipoRival;
    public function findAll(): array;
    public function findByNombre(string $nombre): ?EquipoRival;
}