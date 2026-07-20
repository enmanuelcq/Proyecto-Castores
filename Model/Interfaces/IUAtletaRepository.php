<?php

$root = dirname(__DIR__,2);
require_once $root .'/Model/Entities/Atleta.php';

interface IAtletaRepository
{
    // CRUD básico
    public function create(Atleta $atleta): bool;
    public function update(Atleta $atleta): bool;
    public function delete(string $id): bool;          // elimina físicamente (opcional)
    public function deactivate(string $id): bool;      // cambio lógico a inactivo
    public function activate(string $id): bool;        // reactiva

    // Búsquedas individuales
    public function findById(string $id): ?Atleta;
    public function findByCedula(string $cedula): ?Atleta;

    // Listados
    public function findAll(): array;                  // todos
    public function findAllActive(): array;            // solo activos (status = 1)
    public function findAllInactive(): array;          // solo inactivos

    // Búsquedas por criterios (pueden devolver varios)
    public function findByNombre(string $nombre): array;        // coincidencia en primer o segundo nombre
    public function findByApellido(string $apellido): array;    // coincidencia en apellidos
    public function findByCategoria(int $categoriaId): array;   // atletas de una categoría
    public function findByRepresentante(string $cedulaRepresentante): array; // atletas de un representante
    public function findByStatus(bool $status): array;          // por estado
}