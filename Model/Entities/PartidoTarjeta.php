<?php
// Model/Entities/PartidoTarjeta.php

class PartidoTarjeta
{
    private ?int $id;
    private string $atletaId;
    private int $partidoId;
    private int $tarjetaId;

    public function __construct(?int $id, string $atletaId, int $partidoId, int $tarjetaId)
    {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->partidoId = $partidoId;
        $this->tarjetaId = $tarjetaId;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getPartidoId(): int { return $this->partidoId; }
    public function setPartidoId(int $id): void { $this->partidoId = $id; }

    public function getTarjetaId(): int { return $this->tarjetaId; }
    public function setTarjetaId(int $id): void { $this->tarjetaId = $id; }
}