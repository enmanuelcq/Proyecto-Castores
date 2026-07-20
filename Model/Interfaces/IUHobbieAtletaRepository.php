<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/HobbieAtleta.php';

interface IHobbieAtletaRepository
{
    public function create(HobbieAtleta $relacion): bool;
    public function update(HobbieAtleta $relacion): bool;
    public function delete(int $id): bool;
    public function deleteByAtletaAndHobbie(string $atletaId, int $hobbieId): bool;

    public function findById(int $id): ?HobbieAtleta;
    public function findByAtleta(string $atletaId): array;
    public function findByHobbie(int $hobbieId): array;

    public function exists(string $atletaId, int $hobbieId): bool;
}