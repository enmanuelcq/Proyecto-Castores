<?php

interface IUDesempenioEntrenamientoRepository
{
    public function create(DesempenioEntrenamiento $desempenio): bool;
    public function update(DesempenioEntrenamiento $desempenio): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?DesempenioEntrenamiento;
    public function findByEntrenamientoId(int $entrenamientoId): array;
    public function findByAtletaId(string $atletaId): array;
    public function findOrCreate(int $entrenamientoId, string $atletaId): ?DesempenioEntrenamiento;
    public function countAsistentesByEntrenamiento(int $entrenamientoId): int;
}
