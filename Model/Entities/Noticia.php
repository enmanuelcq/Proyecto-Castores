<?php
class Noticia
{
    private ?int $id;
    private string $titulo;
    private string $contenido;
    private ?string $imagen;
    private ?string $fechaPublicacion;
    private string $usuarioCreador;
    private int $estado;
    private int $esDestacada; // ← NUEVO

    public function __construct(
        ?int $id,
        string $titulo,
        string $contenido,
        ?string $imagen = null,
        ?string $fechaPublicacion = null,
        string $usuarioCreador = '',
        int $estado = 1,
        int $esDestacada = 0 // ← NUEVO
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->contenido = $contenido;
        $this->imagen = $imagen;
        $this->fechaPublicacion = $fechaPublicacion ?? date('Y-m-d H:i:s');
        $this->usuarioCreador = $usuarioCreador;
        $this->estado = $estado;
        $this->esDestacada = $esDestacada; // ← NUEVO
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getTitulo(): string { return $this->titulo; }
    public function getContenido(): string { return $this->contenido; }
    public function getImagen(): ?string { return $this->imagen; }
    public function getFechaPublicacion(): ?string { return $this->fechaPublicacion; }
    public function getUsuarioCreador(): string { return $this->usuarioCreador; }
    public function getEstado(): int { return $this->estado; }
    public function getEsDestacada(): int { return $this->esDestacada; } // ← NUEVO

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setTitulo(string $titulo): void { $this->titulo = $titulo; }
    public function setContenido(string $contenido): void { $this->contenido = $contenido; }
    public function setImagen(?string $imagen): void { $this->imagen = $imagen; }
    public function setFechaPublicacion(?string $fecha): void { $this->fechaPublicacion = $fecha; }
    public function setUsuarioCreador(string $usuario): void { $this->usuarioCreador = $usuario; }
    public function setEstado(int $estado): void { $this->estado = $estado; }
    public function setEsDestacada(int $esDestacada): void { $this->esDestacada = $esDestacada; } // ← NUEVO
}