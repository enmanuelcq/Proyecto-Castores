<?php
// Model/Repository/RepositoryNoticia.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Noticia.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUNoticiaRepository.php';

class RepositoryNoticia implements INoticiaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function limpiarDestacadas(): bool
    {
        $sql = "UPDATE noticias SET es_destacada = 0 WHERE es_destacada = 1";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
    // ============================================================
    // CREATE
    // ============================================================
    public function create(Noticia $noticia): bool
    {
        // Si esta noticia se marca como destacada, limpiar las demás
        if ($noticia->getEsDestacada() == 1) {
            $this->limpiarDestacadas();
        }
        
        $sql = "INSERT INTO noticias (titulo, contenido, imagen, fecha_publicacion, usuario_creador, estado, es_destacada) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $noticia->getTitulo(),
            $noticia->getContenido(),
            $noticia->getImagen(),
            $noticia->getFechaPublicacion(),
            $noticia->getUsuarioCreador(),
            $noticia->getEstado(),
            $noticia->getEsDestacada()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $noticia->setId($id);
        }
        return $result;
    }

public function update(Noticia $noticia): bool
    {
        // Si esta noticia se marca como destacada, limpiar las demás
        if ($noticia->getEsDestacada() == 1) {
            $this->limpiarDestacadas();
        }
        
        $sql = "UPDATE noticias SET 
                    titulo = ?,
                    contenido = ?,
                    imagen = ?,
                    fecha_publicacion = ?,
                    estado = ?,
                    es_destacada = ?
                WHERE noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $noticia->getTitulo(),
            $noticia->getContenido(),
            $noticia->getImagen(),
            $noticia->getFechaPublicacion(),
            $noticia->getEstado(),
            $noticia->getEsDestacada(),
            $noticia->getId()
        ]);
    }

    // ============================================================
    // DELETE (físico)
    // ============================================================
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM noticias WHERE noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ============================================================
    // DEACTIVATE / ACTIVATE (lógico)
    // ============================================================
    public function deactivate(int $id): bool
    {
        $sql = "UPDATE noticias SET estado = 0 WHERE noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function activate(int $id): bool
    {
        $sql = "UPDATE noticias SET estado = 1 WHERE noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ============================================================
    // FIND BY ID
    // ============================================================
    public function findById(int $id): ?Noticia
    {
        $sql = "SELECT * FROM noticias WHERE noticia_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToNoticia($row) : null;
    }

    // ============================================================
    // FIND ALL
    // ============================================================
    public function findAll(): array
    {
        $sql = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToNoticias($rows);
    }

    // ============================================================
    // FIND ALL ACTIVE
    // ============================================================
    public function findAllActive(): array
    {
        $sql = "SELECT * FROM noticias WHERE estado = 1 ORDER BY fecha_publicacion DESC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToNoticias($rows);
    }

    // ============================================================
    // FIND LATEST
    // ============================================================
    public function findLatest(int $limit = 5): array
    {
        $sql = "SELECT * FROM noticias WHERE estado = 1 ORDER BY fecha_publicacion DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToNoticias($rows);
    }

    public function findAllActiveWithLimit(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM noticias 
                WHERE estado = 1 
                ORDER BY fecha_publicacion DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToNoticias($rows);
    }

    public function countActive(): int
    {
        $sql = "SELECT COUNT(*) FROM noticias WHERE estado = 1";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    // ============================================================
    // MAPEO
    // ============================================================
    private function mapRowToNoticia(array $row): Noticia
    {
        return new Noticia(
            (int) $row['noticia_id'],
            $row['titulo'],
            $row['contenido'],
            $row['imagen'] ?? null,
            $row['fecha_publicacion'],
            $row['usuario_creador'],
            (int) $row['estado']
        );
    }

/**
 * Obtiene la noticia destacada (solo una)
 */
    public function findDestacada(): ?Noticia
    {
        $sql = "SELECT * FROM noticias WHERE estado = 1 AND es_destacada = 1 LIMIT 1";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToNoticia($row) : null;
    }

/**
 * Cuenta noticias activas que NO son destacadas
 */
    public function countActiveSinDestacada(): int
    {
        $sql = "SELECT COUNT(*) FROM noticias WHERE estado = 1 AND es_destacada = 0";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

/**
 * Obtiene noticias activas (NO destacadas) con paginación
 */
    public function findAllActiveWithLimitSinDestacada(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM noticias 
                WHERE estado = 1 AND es_destacada = 0
                ORDER BY fecha_publicacion DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToNoticias($rows);
    }


    private function mapRowsToNoticias(array $rows): array
    {
        $noticias = [];
        foreach ($rows as $row) {
            $noticias[] = $this->mapRowToNoticia($row);
        }
        return $noticias;
    }
}