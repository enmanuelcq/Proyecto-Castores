<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Hobbie.php';

interface IHobbieRepository
{
    public function create(Hobbie $hobbie): bool;
    public function update(Hobbie $hobbie): bool;
    public function delete(int $id): bool;

    public function findById(int $id): ?Hobbie;
    public function findByNombre(string $nombre): ?Hobbie;
    public function findAll(): array;
}