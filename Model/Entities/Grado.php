<?php

class Grado
{
    private ?int $id;
    private string $grado;
    private string $atletaId;
    private int $colegioId;

    public function __construct(?int $id, string $grado, string $atletaId, int $colegioId)
    {
        $this->id = $id;
        $this->grado = $grado;
        $this->atletaId = $atletaId;
        $this->colegioId = $colegioId;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getGrado(): string { return $this->grado; }
    public function setGrado(string $grado): void { $this->grado = $grado; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getColegioId(): int { return $this->colegioId; }
    public function setColegioId(int $id): void { $this->colegioId = $id; }
}