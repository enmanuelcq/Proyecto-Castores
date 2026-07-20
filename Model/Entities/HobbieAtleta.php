<?php

class HobbieAtleta
{
    private ?int $id;
    private string $horario;
    private string $atletaId;
    private int $hobbieId;

    public function __construct(?int $id, string $horario, string $atletaId, int $hobbieId)
    {
        $this->id = $id;
        $this->horario = $horario;
        $this->atletaId = $atletaId;
        $this->hobbieId = $hobbieId;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getHorario(): string { return $this->horario; }
    public function setHorario(string $horario): void { $this->horario = $horario; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getHobbieId(): int { return $this->hobbieId; }
    public function setHobbieId(int $id): void { $this->hobbieId = $id; }
}