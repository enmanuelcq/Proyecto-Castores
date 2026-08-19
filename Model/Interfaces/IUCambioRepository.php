<?php
// Model/Interfaces/ICambioRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Cambio.php';

interface ICambioRepository
{
    public function create(Cambio $cambio): bool;
    public function update(Cambio $cambio): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Cambio;
    public function findByPartido(int $partidoId): array;
    public function deleteByPartido(int $partidoId): bool;
}