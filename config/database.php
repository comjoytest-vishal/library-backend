
<?php

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        try {

            // Read .env from project root
            $envPath = dirname(__DIR__) . '/.env';

            if (!file_exists($envPath)) {
                throw new RuntimeException('.env file not found');
            }

            $env = parse_ini_file($envPath);

            if ($env === false) {
                throw new RuntimeException('Unable to read .env file');
            }

            // Database credentials
            $host     = $env['DB_HOST'] ?? '127.0.0.1';
            $port     = $env['DB_PORT'] ?? '3306';
            $dbname   = $env['DB_NAME'] ?? '';
            $username = $env['DB_USER'] ?? 'root';
            $password = $env['DB_PASSWORD'] ?? '';

            if (empty($dbname)) {
                throw new RuntimeException('Database name is missing');
            }

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

            self::$connection = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return self::$connection;

        } catch (PDOException $e) {

            error_log($e->getMessage());

            throw new RuntimeException(
                'Database connection failed.',
                0,
                $e
            );
        }
    }
}
