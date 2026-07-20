<?php

$root = dirname(__DIR__,2);
require_once $root .'/Model/Entities/Usuarios.php';


interface IUsuarioRepository {
    public function create(Usuario $usuario): bool;
    public function update(Usuario $usuario): bool;
    public function delete(string $id): bool;
    public function deactivate(string $id): bool;
    public function activate(string $id): bool;
    public function findById(string $id): ?Usuario;
    public function findByUsername(string $username): ?Usuario;
    public function findAll(): array;
    public function findAllActive(): array;
    public function findByName(string $nombre) : ?Usuario;
    public function findByRol(int $id_rol) : ?Usuario;
    public function findByStatus(bool $status) : ?Usuario;
    public function findByCedula(string $cedula) : ?Usuario;

}