<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/TipoPago.php';

interface ITipoPagoRepository
{
    public function create(TipoPago $tipoPago): bool;
    public function update(TipoPago $tipoPago): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?TipoPago;
    public function findAll(): array;
    public function findByTipo(string $tipo): ?TipoPago;
}