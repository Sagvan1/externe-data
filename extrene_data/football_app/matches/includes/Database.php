<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "../includes/function.php";
require_once "../includes/football.php";

// DB
$database = new Database();
$db = $database->getConnection();

$matchClass = new MatchFootball($db);

//// =========================
//// API OPHALEN
//// =========================
//$url = "https://api.football-data.org/v4/competitions/DED/matches";
//
//$options = [
//        "http" => [
//                "method" => "GET",
//                "header" => "X-Auth-Token: b62a4a0d85d448e5b2d6bd0e8c878288"
//        ]
//];
//
//$context = stream_context_create($options);
//$json = file_get_contents($url, false, $context);
//$data = json_decode($json, true);
//echo "<pre>";
//print_r ($data);
//echo "</pre>" ;
$api_key = "cencored";
$curl_header = curl_init( "https://api.football-data.org/v4/competitions/DED/matches");

curl_setopt($curl_header, CURLOPT_HEADER, false);
curl_setopt($curl_header, CURLOPT_HTTPHEADER, [
//        "method" => "GET",
        "header" => "X-Auth-Token: b62a4a0d85d448e5b2d6bd0e8c878288"
]);
curl_setopt($curl_header, CURLOPT_RETURNTRANSFER, true);

// @todo: error handle json_decode
$data = json_decode(curl_exec($curl_header), true);


// =========================
// SINGLE IMPORT
// =========================
$message = "";
if (isset($_POST["import_all"])) {

    $id = $_POST["match_id"];

    // zoek match in API data


    foreach ($data["matches"] as $match) {


        // duplicate check
        $check = $db->prepare("SELECT id FROM matches WHERE id = :id");
        $check->execute([":id" => $match["id"]]);

        if ($check->rowCount() == 0) {

            $matchClass->create(
                    $match["id"],
                    $match["utcDate"],
                    $match["status"],
                    $match["matchday"],
                    $match["homeTeam"]["id"],
                    $match["awayTeam"]["id"],
                    $match["competition"]["id"]
            );


            $message = " Match succesvol toegevoegd!";
        } else {
            $message = " Match bestaat al in database!";
        }

    }


}

if (isset($_POST["import_one"])) {

    $id = $_POST["match_id"];

    // zoek match in API data
    $selectedMatch = null;

    foreach ($data["matches"] as $match) {
        if ($match["id"] == $id) {
            $selectedMatch = $match;
            break;
        }
    }

    if ($selectedMatch) {

        // duplicate check
        $check = $db->prepare("SELECT id FROM matches WHERE id = :id");
        $check->execute([":id" => $selectedMatch["id"]]);

        if ($check->rowCount() == 0) {

            $matchClass->create(
                    $selectedMatch["id"],
                    $selectedMatch["utcDate"],
                    $selectedMatch["status"],
                    $selectedMatch["matchday"],
                    $selectedMatch["homeTeam"]["id"],
                    $selectedMatch["awayTeam"]["id"],
                    $selectedMatch["competition"]["id"]
            );


            $message = " Match succesvol toegevoegd!";
        } else {
            $message = " Alle matches bestaan al in de database!";
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Single Match Import</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold" href="../index.php">Football App</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="../match_overview.php">Matches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="includes/Database.php">Toevoegen</a>
                </li>

            </ul>

        </div>

    </div>
</nav>

<header class="header">
    <h1> Import 1 Match</h1>
    <form method="post">

        <input type="hidden" name="match_id" value="<?= $match["id"] ?>">

        <button class="btn" type="submit" name="import_all">
            Alles toevoegen aan database
        </button>

    </form>

</header>

<p style="color:blue;"><?= $message ?></p>

<div class="grid">

    <?php foreach ($data["matches"] as $match): ?>

        <div class="card">

            <h3>
                <?= $match["homeTeam"]["id"] ?> vs <?= $match["awayTeam"]["id"] ?>
            </h3>

            <p><?= $match["utcDate"] ?></p>
            <p><?= $match["status"] ?></p>
            <p>Matchday: <?= $match["matchday"] ?></p>

            <!-- IMPORT FORM -->
            <form method="post">

                <input type="hidden" name="match_id" value="<?= $match["id"] ?>">

                <button class="btn" type="submit" name="import_one">
                    Toevoegen aan database
                </button>

            </form>

        </div>

    <?php endforeach; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>