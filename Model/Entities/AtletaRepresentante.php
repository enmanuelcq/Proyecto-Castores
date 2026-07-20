<?php

class AtletaRepresentante
{
    private ?int $id;
    private string $parentesco;
    private string $fechaInscripcion; // formato 'YYYY-MM-DD'
    private string $atletaId;
    private string $cedulaRepresentante;

    public function __construct(
        ?int $id,
        string $parentesco,
        string $fechaInscripcion,
        string $atletaId,
        string $cedulaRepresentante
    ) {
        $this->id = $id;
        $this->parentesco = $parentesco;
        $this->fechaInscripcion = $fechaInscripcion;
        $this->atletaId = $atletaId;
        $this->cedulaRepresentante = $cedulaRepresentante;
    }

    // ──────────── Getters y Setters ────────────

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getParentesco(): string { return $this->parentesco; }
    public function setParentesco(string $parentesco): void { $this->parentesco = $parentesco; }

    public function getFechaInscripcion(): string { return $this->fechaInscripcion; }
    public function setFechaInscripcion(string $fecha): void { $this->fechaInscripcion = $fecha; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getCedulaRepresentante(): string { return $this->cedulaRepresentante; }
    public function setCedulaRepresentante(string $cedula): void { $this->cedulaRepresentante = $cedula; }
}