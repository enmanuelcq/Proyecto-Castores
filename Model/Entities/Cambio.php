<?php
// Model/Entities/Cambio.php

class Cambio
{
    private ?int $id;
    private string $tiempo;
    private string $minuto;
    private ?string $tipoCambio; // Entra, Sale
    private string $atletaEntraId;
    private ?string $atletaSaleId;
    private int $partidoId;

    public function __construct(
        ?int $id,
        string $tiempo,
        string $minuto,
        ?string $tipoCambio,
        string $atletaEntraId,
        ?string $atletaSaleId,
        int $partidoId
    ) {
        $this->id = $id;
        $this->tiempo = $tiempo;
        $this->minuto = $minuto;
        $this->tipoCambio = $tipoCambio;
        $this->atletaEntraId = $atletaEntraId;
        $this->atletaSaleId = $atletaSaleId;
        $this->partidoId = $partidoId;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTiempo(): string { return $this->tiempo; }
    public function setTiempo(string $tiempo): void { $this->tiempo = $tiempo; }

    public function getMinuto(): string { return $this->minuto; }
    public function setMinuto(string $minuto): void { $this->minuto = $minuto; }

    public function getTipoCambio(): ?string { return $this->tipoCambio; }
    public function setTipoCambio(?string $tipo): void { $this->tipoCambio = $tipo; }

    public function getAtletaEntraId(): string { return $this->atletaEntraId; }
    public function setAtletaEntraId(string $id): void { $this->atletaEntraId = $id; }

    public function getAtletaSaleId(): ?string { return $this->atletaSaleId; }
    public function setAtletaSaleId(?string $id): void { $this->atletaSaleId = $id; }

    public function getPartidoId(): int { return $this->partidoId; }
    public function setPartidoId(int $id): void { $this->partidoId = $id; }
}