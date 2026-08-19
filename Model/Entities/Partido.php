<?php
class Partido
{
    private ?int $id;
    private string $fechaPartido;
    private string $duracion;
    private string $estado;
    private string $tipoPartido; // ← NUEVO
    private ?string $nombreCompetencia; // ← NUEVO
    private int $golesFavor;
    private int $golesContra;
    private int $categoriaId;
    private int $equipoId;
    private int $campoId;

    public function __construct(
        ?int $id,
        string $fechaPartido,
        string $duracion,
        string $estado = 'Pendiente',
        string $tipoPartido = 'Amistoso', // ← NUEVO
        ?string $nombreCompetencia = null, // ← NUEVO
        int $golesFavor = 0,
        int $golesContra = 0,
        int $categoriaId = 0,
        int $equipoId = 0,
        int $campoId = 0
    ) {
        $this->id = $id;
        $this->fechaPartido = $fechaPartido;
        $this->duracion = $duracion;
        $this->estado = $estado;
        $this->tipoPartido = $tipoPartido;
        $this->nombreCompetencia = $nombreCompetencia;
        $this->golesFavor = $golesFavor;
        $this->golesContra = $golesContra;
        $this->categoriaId = $categoriaId;
        $this->equipoId = $equipoId;
        $this->campoId = $campoId;
    }

    // ===== Getters =====
    public function getId(): ?int { return $this->id; }
    public function getFechaPartido(): string { return $this->fechaPartido; }
    public function getDuracion(): string { return $this->duracion; }
    public function getEstado(): string { return $this->estado; }
    public function getGolesFavor(): int { return $this->golesFavor; }
    public function getGolesContra(): int { return $this->golesContra; }
    public function getCategoriaId(): int { return $this->categoriaId; }
    public function getEquipoId(): int { return $this->equipoId; }
    public function getCampoId(): int { return $this->campoId; }
    public function getTipoPartido(): string { return $this->tipoPartido; }
    public function getNombreCompetencia(): ?string { return $this->nombreCompetencia; }

    // ===== Setters =====
    public function setId(int $id): void { $this->id = $id; }
    public function setFechaPartido(string $fecha): void { $this->fechaPartido = $fecha; }
    public function setDuracion(string $duracion): void { $this->duracion = $duracion; }
    public function setEstado(string $estado): void { $this->estado = $estado; }
    public function setGolesFavor(int $goles): void { $this->golesFavor = $goles; }
    public function setGolesContra(int $goles): void { $this->golesContra = $goles; }
    public function setCategoriaId(int $id): void { $this->categoriaId = $id; }
    public function setEquipoId(int $id): void { $this->equipoId = $id; }
    public function setCampoId(int $id): void { $this->campoId = $id; }
    public function setTipoPartido(string $tipo): void { $this->tipoPartido = $tipo; }
    public function setNombreCompetencia(?string $nombre): void { $this->nombreCompetencia = $nombre; }
}