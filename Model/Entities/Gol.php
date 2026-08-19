<?php
// Model/Entities/Gol.php

class Gol
{
    private ?int $id;
    private int $cantGoles;
    private string $tiempo;
    private string $descripcion;
    private string $atletaId;
    private int $partidoId;

    public function __construct(
        ?int $id,
        int $cantGoles,
        string $tiempo,
        string $descripcion,
        string $atletaId,
        int $partidoId
    ) {
        $this->id = $id;
        $this->cantGoles = $cantGoles;
        $this->tiempo = $tiempo;
        $this->descripcion = $descripcion;
        $this->atletaId = $atletaId;
        $this->partidoId = $partidoId;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getCantGoles(): int { return $this->cantGoles; }
    public function setCantGoles(int $cant): void { $this->cantGoles = $cant; }

    public function getTiempo(): string { return $this->tiempo; }
    public function setTiempo(string $tiempo): void { $this->tiempo = $tiempo; }

    public function getDescripcion(): string { return $this->descripcion; }
    public function setDescripcion(string $desc): void { $this->descripcion = $desc; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getPartidoId(): int { return $this->partidoId; }
    public function setPartidoId(int $id): void { $this->partidoId = $id; }
}