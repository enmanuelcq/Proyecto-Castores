<?php

class Usuario{

    public string $id;
    public string $cedula;
    public string $nombre;
    public string $apellido;
    public string $username;
    public string $password;
    public string $telefono;
    public string $email;
    public bool $status;
    public int $id_rol;

    public function __construct(string $id, string $cedula, string $username = "", string $password = "", string $nombre = "", string $apellido = "", string $email = "", string $telefono = "", bool $status, int $id_rol = 0,) {

        $this->id = $id;
        $this->cedula = $cedula;
        $this->username = $username;
        $this->password = $password;
        $this->id_rol = $id_rol;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->status = $status;
        $this->email = $email;
    }

    public function getId() : string {
        return $this->id;
    }

    public function setId(string $id): void{
        $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getPassword(): string { 
        return $this->password;
     }
    public function setPassword(string $password): void {
         $this->password = password_hash($password, PASSWORD_DEFAULT); 
    }

    // Método para verificar contraseña
    public function verificarClave(string $clave_plana): bool {
        return password_verify($clave_plana, $this->password);
    }

    public function getIdRol(): int {
        return $this->id_rol;
    }

    public function setIdRol(int $id_rol): void {
        $this->id_rol = $id_rol;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getApellido(): string {
        return $this->apellido;
    }

    public function setApellido(string $apellido): void {
        $this->apellido = $apellido;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getTelefono(): string {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void {
        $this->telefono = $telefono;
    }

    public function getStatus() : bool {
        return $this->status;
    }

    public function setStatus(bool $status): void{
        $this->status = $status;
    }

    public function getCedula(): string {
        return $this->cedula;
    }

    public function setCedula(string $cedula): void {
        $this->cedula = $cedula;
    }
}