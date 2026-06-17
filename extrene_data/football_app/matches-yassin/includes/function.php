<?php
/*
 * gemaakt:Y.ghalit
 * datum:20-05-2026
 * functie: conectie met data base
 */
class Database
{
    //Hier slaan we de verbinding op
    private PDO $pdo;

    //Constructor, automatisch uitgevoerd bij het aanroepen van een nieuw object
    public function __construct()
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "football_app";

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e)
        {
            die("Database verbinding mislukt: ". $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

}