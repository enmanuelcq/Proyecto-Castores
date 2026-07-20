<?php

class Atleta{

    private string $Atleta_id;
    private string $primer_nombre;
    private string $segundo_nombre;
    private string $primer_apellido;
    private string $segundo_apellido;
    private string $fecha_nacimiento;
    private string $sexo;
    private string $cedula;
    private string $pie_dominante;
    private string $direccion;
    private string $foto;
    private bool $atleta_estatus;
    private int $Uniforme_id;

    public function __construct(string $Atleta_id, string $primer_nombre = "", string $segundo_nombre = "", string $primer_apellido = "", string $segundo_apellido = "", string $fecha_nacimiento = "", string $sexo = "", string $cedula = "", string $pie_dominante = "", string $direccion = "", string $foto = "", bool $atleta_estatus, int $Uniforme_id = 0) {

        $this->Atleta_id = $Atleta_id;
        $this->primer_nombre = $primer_nombre;
        $this->segundo_nombre = $segundo_nombre;
        $this->primer_apellido = $primer_apellido;
        $this->segundo_apellido = $segundo_apellido;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->sexo = $sexo;
        $this->atleta_estatus = $atleta_estatus;
        $this->Uniforme_id = $Uniforme_id;
        $this->cedula = $cedula;
        $this->foto = $foto;
        $this->direccion = $direccion;
        $this->pie_dominante = $pie_dominante;
    }

    public function getAtletaId() : string {
        return $this->Atleta_id;
    }

    public function setAtletaId(string $Atleta_id): void{
        $this->Atleta_id;
    }

    public function getPrimerNombre(): string {
        return $this->primer_nombre;
    }

    public function setPrimerNombre(string $primer_nombre): void {
        $this->primer_nombre = $primer_nombre;
    }

    public function getSegundoNombre(): string { 
        return $this->segundo_nombre;
     }

    public function setSegundoNombre(string $segundo_nombre): void {
         $this->segundo_nombre = $segundo_nombre ; 
    }

    public function getPrimerApellido() : string {
        return $this->primer_apellido;
    }

    public function setPrimerApellido(string $Primer_apellido): void {
        $this->primer_apellido = $Primer_apellido;
    }

    public function getSegundoApellido() : string {
        return $this->segundo_apellido;
    }

    public function setSegundoApellido(string $segundo_apellido): void {
        $this->segundo_apellido = $segundo_apellido;
    }

    public function getFechaNacimiento(): string {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento(string $fecha_nacimiento): void {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function getSexo(): string {
        return $this->sexo;
    }

    public function setSexo(string $sexo): void{
        $this->sexo = $sexo;
    }

    public function getAtletaStatus(): bool {
        return $this->atleta_estatus;
    }

    public function setAtletaEstatus(bool $atleta_estatus): void{
        $this->atleta_estatus = $atleta_estatus;
    }

    public function getUniformeId(): int {
        return $this->Uniforme_id;
    }

    public function setUniformeId(int $Uniforme_id): void{
        $this->Uniforme_id = $Uniforme_id;
    }

    public function getCedula(): string{
        return $this->cedula;
    }

    public function setCedula(string $cedula): void{
        $this->cedula = $cedula;
    }

    public function getFoto(): string{
        return $this->foto;
    }

    public function setFoto(string $foto): void{
        $this->foto = $foto;
    }

    public function getDireccion(): string{
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void{
        $this->direccion = $direccion;
    }

    public function getPieDominante(): string{
        return $this->pie_dominante;
    }

    public function setPieDominante(string $pie_dominante): void{
        $this->pie_dominante = $pie_dominante;
    }
}