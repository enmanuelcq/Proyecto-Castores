<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Pago.php';

interface IPagoRepository
{
    public function create(Pago $pago): bool;
    public function update(Pago $pago): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Pago;
    public function findByRepresentante(string $cedulaRepresentante): array;
    public function findByAtleta(string $atletaId): array;
}