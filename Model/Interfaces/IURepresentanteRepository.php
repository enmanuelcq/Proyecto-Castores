<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Representante.php';

interface IRepresentanteRepository
{
    // CRUD básico
    public function create(Representante $representante): bool;
    public function update(Representante $representante): bool;
    public function delete(string $cedula): bool;

    // Búsquedas individuales
    public function findByCedula(string $cedula): ?Representante;
    public function findByCorreo(string $correo): ?Representante;
    public function findByTelefono(string $telefono): ?Representante;

    // Listados
    public function findAll(): array;
    public function findAllActive(): array; // si usamos status

    // Búsquedas por criterios
    public function findByNombre(string $nombre): array;      // coincidencia parcial
    public function findByApellido(string $apellido): array;
    public function findByNombreCompleto(string $nombre, string $apellido): array;

    // Relaciones con Atleta
    public function getAtletasByRepresentante(string $cedulaRepresentante): array; // devuelve array de Atleta
}