<?php

    /** 
     * Configuración de la base de datos
     * Usaremos el patrón singleton para asegurarnos de que solo haya una conexión a la base de datos en toda la aplicación.
     * 
     */

// config/Database.php

class Database {
    // El recipiente único (compartido por todos los archivos)
    private static $instance = null;
    
    // Donde guardaremos el objeto PDO real
    private $connection;

    // El candado: Nadie puede hacer "new Database()" desde fuera
    private function __construct() {
        try {
            // Configuramos los datos de tu servidor
            $host = 'localhost';
            $db   = 'deportivocastores';
            $user = 'root'; 
            $pass = '';     

            // Creamos la instancia de PDO
            $this->connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            
            // Configuramos para que PHP nos avise si hay errores de SQL
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            // Si hay un error, lo registramos en un archivo de log
            error_log("Error de conexión a la base de datos: " . $e->getMessage(), 3, __DIR__ . './error_log/db_errors.log');
            die("No se pudo conectar a la base de datos. Por favor, inténtalo más tarde.");
        }
    }

    // El empleado autorizado: Entrega la conexión única
    public static function getConnection() {
        // Si el recipiente está vacío, llamamos al constructor privado una única vez
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        // Retornamos la conexión que vive dentro de la instancia única
        return self::$instance->connection;
    }
}

?>