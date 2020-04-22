<?php

namespace Models;

use PDO;
use Exception;
use RuntimeException;

/**
 * Class ClassDatabase
 * @package Models
 */
final class ClassDatabase
{
    /** @var PDO $connection */
    private static $connection;

    /**
     * Singleton: Método construtor privado para impedir classe de gerar instâncias.
     * ClassDatabase Constructor.
     */
    private function __construct()
    {}

    private function __clone()
    {}

    /**
     * Método retorno da insância estática de conexão com o banco.
     * @return PDO
     * @throws Exception
     */
    public static function getInstance(): PDO
    {
        if(self::$connection == null) {
            /** @var PDO connection */
            self::$connection = self::dsn();
        }
        return self::$connection;
    }

    /**
     * Médodo de criação dinâmica do dns correto para cada banco de dados
     * @return PDO
     * @throws Exception
     */
    private static function dsn(): PDO
    {
        // Configuração do banco de dados
        $sgdb = DATABASE['sgdb'] ?? NULL;
        $host = DATABASE['host'] ?? NULL;
        $port = DATABASE['port'] ?? NULL;
        $name = DATABASE['name'] ?? NULL;
        $user = DATABASE['user'] ?? NULL;
        $pass = DATABASE['pass'] ?? NULL;
        $options = DATABASE['options'] ?? NULL;

        // Se o parâmetro "sgdb" for informado
        if($sgdb !== null) {
            // Seleciona o banco e cria string de conexão de acordo com o sistema gerenciador de banco de dados
            switch (strtoupper($sgdb)) {
                case 'MYSQL':
                    $port = $port ?? 3306;
                    return new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass, $options); break;
                case 'MSSQL':
                    $port = $port ?? 1433;
                    return new PDO("mssql:host={$host},{$port};dbname={$name}", $user, $pass, $options); break;
                case 'PGSQL':
                    $port = $port ?? 5432;
                    return new PDO("pgsql:dbname={$name};user={$user};password={$pass},host={$host};port={$port}"); break;
                case 'SQLITE': return new PDO("sqlite:{$name}"); break;
                case 'OCI8': return new PDO("oci:dbname={$name}", $user, $pass); break;
                case 'FIREBIRD': return new PDO("firebird:dbname={$name}", $user, $pass); break;
                default: throw new RuntimeException('Tipo de banco de dados inválido!', 'error');
            }
        // Se não existir o parâmetro "sgdb"
        } else {
            throw new RuntimeException('Tipo de banco de dados não informado!');
        } // else
    } // dsn
} // ClassDatabase