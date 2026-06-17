<?php
/*
 *
 */
?>

<?php

    //Includen van de class database
    require_once "includes/Database.php";
    require_once "includes/Games.php";

    //databaseverbinding opzetten
    $db = new database();
    $conn = $db->getConnection();

    $games = new Games($conn);
    $allGames = $games->getAllGames();

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>GameOverview</title>
</head>
<body>

    <header>
        <h1>Overzicht van alle games</h1>
    </header>
    <main>
        <?php
            foreach ($allGames as $game )
            {
               echo "<aricle>";
               echo $game["title"] . "<br>";
               echo $game["description"].  "<br>";
               echo $game["released_at"]. "<br>";
               echo $game["genre"]. "<br>";

               echo "</article><hr>";

            }

        ?>

    </main>


</body>
</html>
