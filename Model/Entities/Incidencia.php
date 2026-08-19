<?php
// Model/Entities/Incidencia.php

class Incidencia
{
    private ?int $id;
    private string $descripcion;
    private string $atletaId;
    private int $partidoId;

    public function __construct(?int $id, string $descripcion, string $atletaId, int $partidoId)
    {
        $this->id = $id;
        $this->descripcion = $descripcion;
        $this->atletaId = $atletaId;
        $this->partidoId = $partidoId;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getDescripcion(): string { return $this->descripcion; }
    public function setDescripcion(string $desc): void { $this->descripcion = $desc; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getPartidoId(): int { return $this->partidoId; }
    public function setPartidoId(int $id): void { $this->partidoId = $id; }
}