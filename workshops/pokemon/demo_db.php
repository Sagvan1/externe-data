<?php
/*
 * Author: R.Evers
 * Date: 22-5-26
 * Overzicht van pokemons uit de Database!
 */
?>
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
        .card input{
            display: block;
            width: 90%;
            margin: 8px auto;
            padding: 6px;
        }
        a{
            display: inline-block;
            padding: 5px 15px;
            background-color: #1d3d8f;
            color: #FFF;
            margin: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<h1>Overzicht pokemons uit database</h1>
<?php
//Verbinding maken met de database
require_once "includes/Database.php";

$database = new Database("pokemonapi");
$db = $database->getConnection();

//Gegevens ophalen uit de database
require_once "includes/Pokemon.php";

$pokemonClass = new Pokemon($db);

//De update uitvoeren
if(isset($_POST["save_edit"]))
{
    $id = $_POST["id"];
    $name = $_POST["name"];
    $image = $_POST["image"];
    $weight = $_POST["weight"];


    //Update statement uitvoeren
    $pokemonClass->update($id, $name, $image, $weight);

    echo "Pokemon $name succesvol aangepast.";
}

//zoekfunctie statement uitvoeren
$search="";
if(isset($_GET["search"])){

    $search = $_GET["search"];

    echo "U heeft gezocht op: $search";

    //functie uitvoeren
    //alleen pokemons binnen zoekterm
    $getAllPokemons = $pokemonClass->searchPokemon($search);
}
else{

    //Alle pokemons worden teruggestuurd
    $getAllPokemons = $pokemonClass->readAll($search);
}


// De delete uitvoeren
if(isset($_GET["delete"]))
{
    $id = $_GET["delete"];

    $pokemonClass->delete($id);

    echo "Pokemon succesvol verwijderd.";
}


$getAllPokemons = $pokemonClass->readAll();

echo "<div class='buttons'>";
echo "<a href='demo_api.php'>Ga naar API overzicht</a>";
echo "<a href='demo_db.php'>Ga naar Database overzicht</a>";
echo "</div>";
?>


<form method="GET" action="demo_db.php">
    <input  type="text" name="search" placeholder="Vul pokemon in....">

    <input type="submit" value="Zoeken">

</form> <hr>

    <?php
echo "<div class='grid'>";
foreach ($getAllPokemons as $pokemon)
{
    $id = htmlspecialchars($pokemon["id"]);
    $name = htmlspecialchars($pokemon["name"]);
    $image = htmlspecialchars($pokemon["image"]);
    $weight = htmlspecialchars($pokemon["weight"]);

    echo "<div class='card'>";

    if (!empty($image)) {
        echo "<img src='$image' alt='$name'>";
    }

    echo "<h2>$name</h2>";

    //Link om pokemon te bewerken
    echo "<a href='demo_db.php?edit=$id' > Bewerken</a>";
    echo "<a href='demo_db.php?delete=$id'> Verwijderen</a>";

    if(isset($_GET["edit"])) {
    //Formulier maken om deze pokemon te vewerken
        echo "<form method='POST' action='demo_db.php'>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<input type='text' name='name' value='$name'>";
        echo "<input type='text' name='image' value='$image'>";
        echo "<input type='number' name='image' value='$weight'>";
        echo "<input type='submit' name='save_edit' value='Opslaan'>";
        echo "</form>";
    }

    echo "</div>";
}
echo "</div>";
?>

</body>
</html>
