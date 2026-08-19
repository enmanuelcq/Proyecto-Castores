<?php
// Model/Interfaces/IPartidoTarjetaRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/PartidoTarjeta.php';

interface IPartidoTarjetaRepository
{
    public function create(PartidoTarjeta $pt): bool;
    public function delete(int $id): bool;
    public function findByPartido(int $partidoId): array;
    public function findByAtleta(string $atletaId): array;
    public function deleteByPartido(int $partidoId): bool;
}