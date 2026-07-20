<?php

class Categoria
{
    private ?int $id;
    private string $subCategoria;

    public function __construct(?int $id, string $subCategoria)
    {
        $this->id = $id;
        $this->subCategoria = $subCategoria;
    }

    // ──────────── Getters y Setters ────────────

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getSubCategoria(): string { return $this->subCategoria; }
    public function setSubCategoria(string $subCategoria): void { $this->subCategoria = $subCategoria; }
}