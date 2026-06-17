<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokemon overzicht</title>

    <style>
        .grid
        {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .card{
            background-color: #2e66ff;
            padding: 20px;
            text-align: center;
        }

    </style>
</head>
<body>
<h1>Pokemon overzicht vanuit API</h1>
<a href='demo_db.php'>Ga naar Database overzicht</a><br>
<?php

/*
  * url: https://pokeapi.co/api/v2/pokemon?limit=20
  *
  */
//Inladen van de classes
require_once "includes/Database.php";
require_once "includes/Pokemon.php";

//verbinden met de database
$database = new Database("pokemonapi");
$db = $database->getConnection();

//OPhalen gegevens verstuurd vanaf formulier
if(isset($_POST["add_pokemon"])){
    $name = $_POST["name"];
    $image = $_POST["image"];
    $weight = $_POST["weight"];

    $pokemonClass = new Pokemon($db);
    $pokemonClass->create($name, $image, $weight);

    echo "Pokemon succesvol toegevoegd aan de database";

}



$pokemonApi = new Pokemon($db);
$allPokemons = $pokemonApi->getPokemonByApi();

//        echo "<pre>";
//            var_dump($allPokemons);
//        echo "</pre>";

echo "<div class='grid'>";

//Door alle resultaten heenlopen.
foreach($allPokemons as $pokemon)
{

    //API call moeten doen met de url die bij deze pokemon hoort.

    //Stap 1:URL
    $name = htmlspecialchars($pokemon["name"]);
    $image = htmlspecialchars($pokemon["image"]);
    $weight = htmlspecialchars($pokemon["weight"]);

    echo "<div class='card'>";
    //Stap 5: image tonen
    echo "<img src='$image'>";

    echo "<h2>".$name."</h2>";
    echo $weight;

    //onzichtbaar formulier maken
    echo "<form method='POST'>";
    echo "<input type='hidden' name='name' value='".$name."'>";
    echo "<input type='hidden' name='image' value='".$image."'>";
    echo "<input type='hidden' name='image' value='".$weight."'>";
    echo "<button type='submit' name='add_pokemon'>Toevoegen aan database</button>";
    echo "</form>";

    echo "</div>";
}

echo "</div>";

?>
</body>
</html>