<?php

class Colegio
{
    private ?int $id;
    private string $nombre;
    private string $direccion;

    public function __construct(?int $id, string $nombre, string $direccion)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getNombre(): string { return $this->nombre; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }

    public function getDireccion(): string { return $this->direccion; }
    public function setDireccion(string $direccion): void { $this->direccion = $direccion; }
}