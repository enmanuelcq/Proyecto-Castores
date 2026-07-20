<?php

class Representante
{
    private string $cedula;
    private string $nombre;
    private string $apellido;
    private string $correo;
    private string $ocupacion;
    private string $telefono;
    private ?bool $status; // opcional, para manejo lógico

    public function __construct(
        string $cedula,
        string $nombre,
        string $apellido,
        string $correo,
        string $ocupacion,
        string $telefono,
        ?bool $status = null // si no está en la BD, podemos omitirlo
    ) {
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->ocupacion = $ocupacion;
        $this->telefono = $telefono;
        $this->status = $status ?? true; // por defecto activo
    }

    // ──────────── Getters y Setters ────────────

    public function getCedula(): string { return $this->cedula; }
    public function setCedula(string $cedula): void { $this->cedula = $cedula; }

    public function getNombre(): string { return $this->nombre; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }

    public function getApellido(): string { return $this->apellido; }
    public function setApellido(string $apellido): void { $this->apellido = $apellido; }

    public function getCorreo(): string { return $this->correo; }
    public function setCorreo(string $correo): void { $this->correo = $correo; }

    public function getOcupacion(): string { return $this->ocupacion; }
    public function setOcupacion(string $ocupacion): void { $this->ocupacion = $ocupacion; }

    public function getTelefono(): string { return $this->telefono; }
    public function setTelefono(string $telefono): void { $this->telefono = $telefono; }

    public function getStatus(): bool { return $this->status; }
    public function setStatus(bool $status): void { $this->status = $status; }
}