<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Operacion.php';

interface IOperacionRepository
{
    public function create(Operacion $operacion): bool;
    public function delete(int $id): bool;
    public function findByPago(int $pagoId): array;
    public function existsReferencia(int $tipoPagoId, string $banco, string $referencia): bool;
}