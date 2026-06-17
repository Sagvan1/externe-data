<?php
/*
 * Author: Sagvan
 * Date 18-5-26
 * teams  class
 */
class Team
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create($params)
    {
        $sql = "
        INSERT INTO teams (id, name, crest, founded, clubColors, venue, competition_id) 
        VALUES (:id, :name, :crest, :founded, :clubColors, :venue, :competition_id)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $params["id"]);
        $stmt->bindParam(":name", $params["name"]);
        $stmt->bindParam(":crest", $params["crest"]);
        $stmt->bindParam(":founded", $params["founded"]);
        $stmt->bindParam(":clubColors", $params["clubColors"]);
        $stmt->bindParam(":venue", $params["venue"]);
        $stmt->bindParam(":competition_id", $params["competition_id"]);

        $stmt->execute();
    }

    // READ
    public function readAll()
    {
        $sql = " SELECT teams.*, competitions.name AS competition_name
        FROM teams
        JOIN competitions ON teams.competition_id = competitions.id";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    // DELETE
    public function delete($id)
    {
        $sql = "DELETE FROM teams WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id);

        $stmt->execute();
    }

    // UPDATE
    public function update($id, $name, $founded, $clubColors, $venue)
    {
        $sql = "UPDATE teams  SET name = :name, founded = :founded, clubColors = :clubColors, venue = :venue
        WHERE id = :id ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":founded", $founded);
        $stmt->bindParam(":clubColors", $clubColors);
        $stmt->bindParam(":venue", $venue);

        $stmt->execute();
    }

    public function searchTeam($search)
    {
        //query die team zoekt met search
        $sql = "SELECT * 
                FROM teams
                WHERE name LIKE :search";

        $stmt = $this->conn->prepare($sql);

        $search = "%" . $search . "%";

        $stmt->bindParam(":search", $search);

        //Uitvoeren query
        $stmt->execute();

        //Resulaten ophalen en terugsturen
        return $stmt->fetchAll();
    }
}