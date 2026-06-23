<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * Competitions class
 */

class Competition
{
    // Databaseverbinding opslaan
    private PDO $conn;

    // Constructor ontvangt de databaseverbinding
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // CREATE
    // Nieuwe competitie toevoegen aan de database
    public function create($params)
    {
        $sql = "INSERT INTO competitions (id, name, code, type, emblem)
            VALUES (:id, :name, :code, :type, :emblem)";

        // Query voorbereiden
        $stmt = $this->conn->prepare($sql);

        // Waarden koppelen aan de placeholders
        $stmt->bindValue(":id", $params["id"]);
        $stmt->bindValue(":name", $params["name"]);
        $stmt->bindValue(":code", $params["code"]);
        $stmt->bindValue(":type", $params["type"]);
        $stmt->bindValue(":emblem", $params["emblem"]);

        // Query uitvoeren
        $stmt->execute();
    }

    // READ
    // Alle competities ophalen uit de database
    public function readAll()
    {
        $sql = "SELECT * FROM competitions";

        // Query uitvoeren
        $stmt = $this->conn->query($sql);

        $stmt->execute();

        // Alle resultaten teruggeven
        return $stmt->fetchAll();
    }

    // DELETE
    // Competitie verwijderen op basis van ID
    public function delete($id)
    {
        $sql = "DELETE FROM competitions WHERE id = :id";

        // Query voorbereiden
        $stmt = $this->conn->prepare($sql);

        // ID koppelen aan de placeholder
        $stmt->bindParam(":id", $id);

        // Query uitvoeren
        $stmt->execute();
    }

    // UPDATE
    // Gegevens van een bestaande competitie aanpassen
    public function update(int $id, string $name, string $code, string $type)
    {
        $sql = "UPDATE competitions 
                SET name = :name,
                    code = :code,
                    type = :type
                WHERE id = :id ";

        // Query voorbereiden
        $stmt = $this->conn->prepare($sql);

        // Waarden koppelen aan de placeholders
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":type", $type);

        // Query uitvoeren
        $stmt->execute();
    }

    // Zoeken naar competities op naam of code
    public function searchCompetition($search)
    {
        // Query die competities zoekt op naam of competitiecode
        $sql = "SELECT * 
                FROM competitions 
                WHERE name LIKE :search
                OR code LIKE :search";

        // Query voorbereiden
        $stmt = $this->conn->prepare($sql);

        // Wildcards toevoegen voor gedeeltelijke overeenkomsten
        $search = "%" . $search . "%";

        // Zoekterm koppelen aan de placeholder
        $stmt->bindParam(":search", $search);

        // Query uitvoeren
        $stmt->execute();

        // Resultaten ophalen en terugsturen
        return $stmt->fetchAll();
    }
}