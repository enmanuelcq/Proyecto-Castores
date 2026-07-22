<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/CuotaMensual.php';

interface ICuotaMensualRepository
{
    public function create(CuotaMensual $cuota): bool;
    public function update(CuotaMensual $cuota): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?CuotaMensual;
    public function findByAtleta(string $atletaId): array;
    public function findByAtletaAndYear(string $atletaId, int $anio): array;
    public function updateEstado(int $cuotaId, string $estado): bool;
    public function countByStatus(string $estado): int;
}