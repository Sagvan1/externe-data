<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * Database class
 */
class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = "localhost";
        $dbname = "football_app";
        $username = "root";
        $password = "";

        try {

            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password
            );

            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch(PDOException $e) {

            die("Connectie mislukt: " . $e->getMessage());

        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}