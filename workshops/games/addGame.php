<?php

/*
 * Author: Sagvan
 * Date: 11-5-2026
 * addGame page
 *
 */


if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $title = $_POST["title"];
    $description = $_POST["description"];
    $releasedAt = $_POST["released_at"];
    $genreId = $_POST["genre_id"];
    $platformId = $_POST["platform_id"];


    require_once "includes/Database.php";
    require_once "includes/Games.php";

    $database = new Database();
    $conn = $database->getConnection();
    $games = new Games($conn);

    $games->addGame($title, $description, $releasedAt, $genreId, $platformId);

    echo  "Game succesvol toegevoegd";

}


?>

<form method="POST">

    <label>Titel:</label>
    <input type="text" name="title" required>
    <br> </br>

    <label>Beschrijving:</label>
    <textarea name="description" required></textarea>
    <br> </br>


    <label>Releasedatum:</label>
    <input type="date" name="released_at" required>
    <br> </br>

    <label>Genre ID:</label>
    <input type="number" name="released_at" required>
    <br> </br>

    <label>Platform ID:</label>
    <input type="number" name="platform_id" required>


    <button type="submit">
        Game toevoegen
    </button>

</form>
