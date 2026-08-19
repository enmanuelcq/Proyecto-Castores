<?php
// Model/Interfaces/IGolRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Gol.php';

interface IGolRepository
{
    public function create(Gol $gol): bool;
    public function update(Gol $gol): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Gol;
    public function findByPartido(int $partidoId): array;
    public function findByAtleta(string $atletaId): array;
    public function deleteByPartido(int $partidoId): bool;
}