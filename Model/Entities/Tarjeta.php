<?php
// Model/Entities/Tarjeta.php

class Tarjeta
{
    private ?int $id;
    private string $tarjeta; // Amarilla, Roja, etc.

    public function __construct(?int $id, string $tarjeta)
    {
        $this->id = $id;
        $this->tarjeta = $tarjeta;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTarjeta(): string { return $this->tarjeta; }
    public function setTarjeta(string $tarjeta): void { $this->tarjeta = $tarjeta; }
}