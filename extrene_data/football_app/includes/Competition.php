<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * competitions class
 */
class Competition
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create($params)
    {
        $sql = "INSERT INTO competitions (id, name, code, type, emblem)
            VALUES (:id, :name, :code, :type, :emblem)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $params["id"]);
        $stmt->bindValue(":name", $params["name"]);
        $stmt->bindValue(":code", $params["code"]);
        $stmt->bindValue(":type", $params["type"]);
        $stmt->bindValue(":emblem", $params["emblem"]);

        $stmt->execute();
    }

    // READ
    public function readAll()
    {
        $sql = "SELECT * FROM competitions";

        $stmt = $this->conn->query($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // DELETE
    public function delete($id)
    {
        $sql = "DELETE FROM competitions WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id);

        $stmt->execute();
    }

    // UPDATE
    public function update(int $id, string $name, string $code, string $type)
    {
        $sql = "UPDATE competitions SET name = :name, code = :code,type = :type WHERE id = :id ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":type", $type);

        $stmt->execute();
    }

    public function searchCompetition($search)
    {
        //query die pokemon zoekt met $search
        $sql = "SELECT * 
                FROM competitions 
                WHERE name LIKE :search
                OR code LIKE :search";

        $stmt = $this->conn->prepare($sql);

        $search = "%" . $search . "%";

        $stmt->bindParam(":search", $search);

        //Uitvoeren query
        $stmt->execute();

        //Resulaten ophalen en terugsturen
        return $stmt->fetchAll();
    }
}