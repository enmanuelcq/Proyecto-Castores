<?php

interface IUEntrenamientoRepository
{
    public function create(Entrenamiento $entrenamiento): bool;
    public function update(Entrenamiento $entrenamiento): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Entrenamiento;
    public function findAll(): array;
    public function findByCategoria(string $categoria): array;
    public function findByEstado(string $estado): array;
    public function findByUsuarioCedula(string $cedula): array;
    public function findByFecha(string $fecha): array;
    public function findByRangoFechas(string $desde, string $hasta): array;
    public function findProximos(int $limite = 5): array;
    public function countByEstado(string $estado): int;
    public function countByCategoria(string $categoria): int;
    public function countByUsuarioCedula(string $cedula): int;
    public function estadisticasEntrenador(string $cedula): array;
}
