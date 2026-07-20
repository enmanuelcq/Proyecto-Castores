<?php

class Salud
{
    private ?int $id;
    private string $tipoSangre;
    private string $alergias;
    private string $padecimiento;
    private string $medicamentoNoTomar;
    private string $lesionesPrevias;
    private ?string $polizaSeguro;
    private ?string $vigencia; // año (puede ser string o int)
    private ?string $empresa;
    private ?string $cobertura;
    private ?string $nroPoliza;
    private ?string $nombreMedico;
    private ?string $telefonoMedico;
    private string $atletaId;

    public function __construct(
        ?int $id,
        string $tipoSangre,
        string $alergias,
        string $padecimiento,
        string $medicamentoNoTomar,
        string $lesionesPrevias,
        ?string $polizaSeguro,
        ?string $vigencia,
        ?string $empresa,
        ?string $cobertura,
        ?string $nroPoliza,
        ?string $nombreMedico,
        ?string $telefonoMedico,
        string $atletaId
    ) {
        $this->id = $id;
        $this->tipoSangre = $tipoSangre;
        $this->alergias = $alergias;
        $this->padecimiento = $padecimiento;
        $this->medicamentoNoTomar = $medicamentoNoTomar;
        $this->lesionesPrevias = $lesionesPrevias;
        $this->polizaSeguro = $polizaSeguro;
        $this->vigencia = $vigencia;
        $this->empresa = $empresa;
        $this->cobertura = $cobertura;
        $this->nroPoliza = $nroPoliza;
        $this->nombreMedico = $nombreMedico;
        $this->telefonoMedico = $telefonoMedico;
        $this->atletaId = $atletaId;
    }

    // Getters y setters
    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id; }

    public function getTipoSangre(): string { return $this->tipoSangre; }
    public function setTipoSangre(string $tipo): void { $this->tipoSangre = $tipo; }

    public function getAlergias(): string { return $this->alergias; }
    public function setAlergias(string $alergias): void { $this->alergias = $alergias; }

    public function getPadecimiento(): string { return $this->padecimiento; }
    public function setPadecimiento(string $padecimiento): void { $this->padecimiento = $padecimiento; }

    public function getMedicamentoNoTomar(): string { return $this->medicamentoNoTomar; }
    public function setMedicamentoNoTomar(string $medicamento): void { $this->medicamentoNoTomar = $medicamento; }

    public function getLesionesPrevias(): string { return $this->lesionesPrevias; }
    public function setLesionesPrevias(string $lesiones): void { $this->lesionesPrevias = $lesiones; }

    public function getPolizaSeguro(): ?string { return $this->polizaSeguro; }
    public function setPolizaSeguro(?string $poliza): void { $this->polizaSeguro = $poliza; }

    public function getVigencia(): ?string { return $this->vigencia; }
    public function setVigencia(?string $vigencia): void { $this->vigencia = $vigencia; }

    public function getEmpresa(): ?string { return $this->empresa; }
    public function setEmpresa(?string $empresa): void { $this->empresa = $empresa; }

    public function getCobertura(): ?string { return $this->cobertura; }
    public function setCobertura(?string $cobertura): void { $this->cobertura = $cobertura; }

    public function getNroPoliza(): ?string { return $this->nroPoliza; }
    public function setNroPoliza(?string $nroPoliza): void { $this->nroPoliza = $nroPoliza; }

    public function getNombreMedico(): ?string { return $this->nombreMedico; }
    public function setNombreMedico(?string $nombre): void { $this->nombreMedico = $nombre; }

    public function getTelefonoMedico(): ?string { return $this->telefonoMedico; }
    public function setTelefonoMedico(?string $telefono): void { $this->telefonoMedico = $telefono; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }
}