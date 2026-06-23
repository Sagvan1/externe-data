<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * Database class
 */

class Database
{
    // PDO-object voor de databaseverbinding
    private PDO $pdo;

    // Constructor wordt automatisch uitgevoerd bij het maken van een object
    public function __construct()
    {
        // Databasegegevens
        $host = "localhost";
        $dbname = "football_app";
        $username = "root";
        $password = "";

        try {

            // Verbinding maken met de MySQL-database
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password
            );

            // PDO instellen zodat fouten als exceptions worden weergegeven
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch(PDOException $e) {

            // Foutmelding tonen als de verbinding mislukt
            die("Connectie mislukt: " . $e->getMessage());

        }
    }

    // Geeft de actieve databaseverbinding terug
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}