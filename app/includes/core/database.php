<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: database - v0.2
 */

namespace Alphafable\Core;
require_once sprintf('%s/../global.php', __DIR__);

class Database extends \PDO {
    private ?\PDO $conn = null;

    function __construct(string $dsn, string $username, string $password) {
        $this->conn = new parent($dsn, $username, $password, [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_PERSISTENT => true,
        ]);
    }
    
    function safeFetch(string $query, array $params=[]) : array {
        if (is_null($this->conn)) 
            die('Database is currently closed!');

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch (\Exception) {
            return [];
        }
    }
    
    function safeQuery(string $query, array $params=[]) : int {
        if (is_null($this->conn)) 
            die('Database is currently closed!');

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return $stmt->rowCount();
        } catch (\Exception) {
            return 0;
        }
    }

    function getCount(string $query, array $params=[]) : int {
        if (is_null($this->conn)) 
            die('Database is currently closed!');

        try {
            $stmt = $this->safeFetch($query, $params);
            return count($stmt);
        } catch (\Exception) {
            return 0;
        }
    }
}