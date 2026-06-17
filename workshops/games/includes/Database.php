<?php
/*
 * Database class
 *
 */
class Database
{
    //Hier staan we de verbinding op

    private PDO $pdo;

    //Constructor, automatisch uitgevoerd bij het aaroepen van een nieuw

    public function __construct()
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gamevault";

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            die("Database verbinding mislukt: " . $e->getMessage());

        }

    }

        public function getConnection(): PDO
        {
            return $this->pdo;
        }





}