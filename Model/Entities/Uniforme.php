<?php

class Uniforme
{
    private ?int $id;
    private string $franelaTalla;
    private int $franelaNumero;
    private string $shortTalla;
    private string $zapatosTalla;

    public function __construct(?int $id, string $franelaTalla, int $franelaNumero, string $shortTalla, string $zapatosTalla)
    {
        $this->id = $id;
        $this->franelaTalla = $franelaTalla;
        $this->franelaNumero = $franelaNumero;
        $this->shortTalla = $shortTalla;
        $this->zapatosTalla = $zapatosTalla;
    }

    // ──────────── Getters y Setters ────────────

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getFranelaTalla(): string { return $this->franelaTalla; }
    public function setFranelaTalla(string $talla): void { $this->franelaTalla = $talla; }

    public function getFranelaNumero(): int { return $this->franelaNumero; }
    public function setFranelaNumero(int $numero): void { $this->franelaNumero = $numero; }

    public function getShortTalla(): string { return $this->shortTalla; }
    public function setShortTalla(string $talla): void { $this->shortTalla = $talla; }

    public function getZapatosTalla(): string { return $this->zapatosTalla; }
    public function setZapatosTalla(string $talla): void { $this->zapatosTalla = $talla; }
}