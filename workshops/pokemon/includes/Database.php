<?php
/*
 * Author: sagvan
 * Date: 20-5-26
 * Class: Database class
 */

class Database
{
    // Hier slaan we de PDO databaseverbinding op
    private PDO $pdo;

    /**
     * Constructor
     * Deze wordt automatisch uitgevoerd wanneer je:
     * new Database("databasenaam") gebruikt.
     */
    public function __construct(string $dbname)
    {
        // Gegevens van de databaseverbinding
        $host = "localhost";
        $username = "root";
        $password = "";

        try {

            // Maak verbinding met de MySQL database
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password
            );

            // Zorg ervoor dat fouten als errors worden weergegeven
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            // Toon een foutmelding als de verbinding mislukt
            die("Database verbinding mislukt: " . $e->getMessage());
        }
    }

    /**
     * Geeft de databaseverbinding terug
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
