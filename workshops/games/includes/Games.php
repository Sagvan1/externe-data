<?php

class Games
{
    private PDO $conn;


    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAllGames(): array
    {

        //$sql = "SELECT * FROM games";
        //Query aanpassen om titel, description, release datum, NAAm van Genre
        $sql = "SELECT games.title, games.description, games.released_at, genres.name AS genre
                FROM games 
                JOIN genres ON games.genre_id = genres.genre_id";

        $result = $this->conn->query($sql);


        return $result->fetchAll();
    }

    //Nieuwe game toevoegen
    public function addGame(string $title, string $description, string $released_at, string $genre_id, string $platform_id)
    {
        $sql = "INSERT INTO games(title, description, released_at, genre_id, platform_id) VALUES (:title, :description, :released_at, :genre_id, :platform_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':released_at', $released_at);
        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->bindParam(':platform_id', $platform_id);

        return $stmt->execute();

    }



}