<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Salud.php';

interface ISaludRepository
{
    public function create(Salud $salud): bool;
    public function update(Salud $salud): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Salud;
    public function findByAtletaId(string $atletaId): ?Salud;   // 1:1 con atleta
    
}