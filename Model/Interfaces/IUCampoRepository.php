<?php
// Model/Interfaces/ICampoRepository.php

require_once dirname(__DIR__, 2) . '/Model/Entities/Campo.php';

interface ICampoRepository
{
    public function create(Campo $campo): bool;
    public function update(Campo $campo): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Campo;
    public function findAll(): array;
    public function findByNombre(string $nombre): ?Campo;
}