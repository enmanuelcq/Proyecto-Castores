<?php

class CategoriaAtleta
{
    private ?int $id;
    private string $anio;
    private string $atletaId;
    private int $categoriaId;

    public function __construct(?int $id, string $anio, string $atletaId, int $categoriaId)
    {
        $this->id = $id;
        $this->anio = $anio;
        $this->atletaId = $atletaId;
        $this->categoriaId = $categoriaId;
    }

    // ──────────── Getters y Setters ────────────

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getAnio(): string { return $this->anio; }
    public function setAnio(string $anio): void { $this->anio = $anio; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getCategoriaId(): int { return $this->categoriaId; }
    public function setCategoriaId(int $id): void { $this->categoriaId = $id; }
}