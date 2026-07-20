<?php

class Hobbie
{
    private ?int $id;
    private string $nombre;

    public function __construct(?int $id, string $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getNombre(): string { return $this->nombre; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
}