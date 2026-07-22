<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/PagoCuota.php';

interface IPagoCuotaRepository
{
    public function create(PagoCuota $pagoCuota): bool;
    public function delete(int $id): bool;
    public function findByPago(int $pagoId): array;
    public function findByCuota(int $cuotaId): array;
    public function sumAbonadoByCuota(int $cuotaId): float;
    
}