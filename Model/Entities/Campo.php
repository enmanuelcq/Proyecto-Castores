<?php
// Model/Entities/Campo.php

class Campo
{
    private ?int $id;
    private string $nombre;
    private string $direccion;
    private ?string $mapa;

    public function __construct(?int $id, string $nombre, string $direccion, ?string $mapa = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->mapa = $mapa;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNombre(): string { return $this->nombre; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }

    public function getDireccion(): string { return $this->direccion; }
    public function setDireccion(string $direccion): void { $this->direccion = $direccion; }

    public function getMapa(): ?string { return $this->mapa; }
    public function setMapa(?string $mapa): void { $this->mapa = $mapa; }
}