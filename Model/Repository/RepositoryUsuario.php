<?php

$root = dirname(__DIR__,2);
require_once $root .'/Model/Entities/Usuarios.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUsuarioRepository.php';


class RepositoryUsuario implements IUsuarioRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ─────────────────────── CREATE ───────────────────────
    public function create(Usuario $usuario): bool
    {
        $sql = "INSERT INTO usuario 
                (usuario_id, usuario_cedula, usuario_usuario, usuario_clave, usuario_nombre, usuario_apellido, usuario_correo, usuario_telefono, usuario_status, rol_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $usuario->getId(),
            $usuario->getCedula(),
            $usuario->getUsername(),
            $usuario->getPassword(),      // ya debería estar hasheada
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getEmail(),
            $usuario->getTelefono(),
            $usuario->getStatus(),  // bool → 0/1
            $usuario->getIdRol()
        ]);
    }

    // ─────────────────────── UPDATE ───────────────────────
    public function update(Usuario $usuario): bool
    {
        $sql = "UPDATE usuario SET 
                    usuario_cedula = ?,
                    usuario_usuario = ?,
                    usuario_clave = ?,
                    usuario_nombre = ?,
                    usuario_apellido = ?,
                    usuario_correo = ?,
                    usuario_telefono = ?,
                    usuario_status = ?,
                    rol_id = ?
                WHERE usuario_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $usuario->getCedula(),
            $usuario->getUsername(),
            $usuario->getPassword(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getEmail(),
            $usuario->getTelefono(),
            $usuario->getStatus(),
            $usuario->getIdRol(),
            $usuario->getId()
        ]);
    }

    // ─────────────────────── DELETE ───────────────────────
    public function delete(string $id): bool
    {
        $sql = "DELETE FROM usuario WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ─────────────────────── FIND BY ID ───────────────────────
    public function findById(string $id): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE usuario_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUsuario($row) : null;
    }

    // ─────────────────────── FIND BY USERNAME ───────────────────────
    public function findByUsername(string $username): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE usuario_usuario = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUsuario($row) : null;
    }

    // ─────────────────────── FIND ALL ───────────────────────
    public function findAll(): array
    {
        $sql = "SELECT * FROM usuario";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($rows as $row) {
            $usuarios[] = $this->mapRowToUsuario($row);
        }
        return $usuarios;
    }

    // ─────────────────────── FIND BY NAME ───────────────────────
    public function findByName(string $nombre): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE usuario_nombre = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUsuario($row) : null;
    }

    // ─────────────────────── FIND BY ROL ───────────────────────
    public function findByRol(int $id_rol): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE rol_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_rol]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUsuario($row) : null;
    }

    // ─────────────────────── FIND ALL BY ROL ───────────────────────
    public function findAllByRol(int $id_rol): array
    {
        $sql = "SELECT * FROM usuario WHERE rol_id = ? AND usuario_status = 1 ORDER BY usuario_nombre, usuario_apellido";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_rol]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($rows as $row) {
            $usuarios[] = $this->mapRowToUsuario($row);
        }
        return $usuarios;
    }

    // ─────────────────────── FIND BY STATUS ───────────────────────
    public function findByStatus(bool $status): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE usuario_status = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int) $status]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUsuario($row) : null;
    }

    // ─────────────────────── FIND BY CEDULA ───────────────────────
    public function findByCedula(string $cedula): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE usuario_cedula = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cedula]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUsuario($row) : null;
    }

    // ──────────── PRIVADO: mapea un array asociativo a un objeto Usuario ────────────
    private function mapRowToUsuario(array $row): Usuario
    {
        return new Usuario(
            $row['usuario_id'],
            $row['usuario_cedula'],
            $row['usuario_usuario'],
            $row['usuario_clave'],
            $row['usuario_nombre'],
            $row['usuario_apellido'],
            $row['usuario_correo'],
            $row['usuario_telefono'],
            (bool) $row['usuario_status'],   // convertimos a booleano
            (int) $row['rol_id']
        );
    }

    public function deactivate(string $id): bool
    {
        $sql = "UPDATE usuario SET usuario_status = 0 WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function activate(string $id): bool
    {
        $sql = "UPDATE usuario SET usuario_status = 1 WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findAllActive(): array
    {
        $sql = "SELECT * FROM usuario WHERE usuario_status = 1";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];
        foreach ($rows as $row) {
            $usuarios[] = $this->mapRowToUsuario($row);
        }
        return $usuarios;
    }
}

?>