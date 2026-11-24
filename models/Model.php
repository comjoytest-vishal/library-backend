
<?php

require_once __DIR__ . '/../config/database.php';

class Model
{
    protected PDO $db;

    public function __construct()
    {
        // Get database connection
        $this->db = Database::connect();
    }
}
