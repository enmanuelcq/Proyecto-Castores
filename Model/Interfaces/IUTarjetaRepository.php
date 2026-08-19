<?php
// Model/Interfaces/ITarjetaRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Tarjeta.php';

interface ITarjetaRepository
{
    public function create(Tarjeta $tarjeta): bool;
    public function update(Tarjeta $tarjeta): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Tarjeta;
    public function findAll(): array;
    public function findByTipo(string $tipo): ?Tarjeta;
}