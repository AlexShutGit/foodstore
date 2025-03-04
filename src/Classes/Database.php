<?php

declare(strict_types=1);

namespace App\Classes;

use InvalidArgumentException;
use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @param mixed $dsn
     * @param string $username
     * @param string $password
     */
    public function __construct($dsn, string $username = '', string $password = '') {
        try {
            $this->connection = new PDO($dsn, $username, $password);
        } catch (PDOException $exception) {
            throw new InvalidArgumentException('Database error: '. $exception->getMessage());
        }
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * @param string $sql
     * @param array $params
     * 
     * @return array
     */
    public function fetchAll(string $sql, array $params = []): array {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * 
     * @return bool
     */
    public function query(string $sql, array $params = []): bool {
        $statement = $this->connection->prepare($sql);
        return $statement->execute($params);
    }
}