<?php
require_once "function.php";
require_once "football.php";

// DB
$database = new Database();
$db = $database->getConnection();

$matchClass = new MatchFootball($db);

// =========================
// API OPHALEN
// =========================
$url = "https://api.football-data.org/v4/competitions/2003/matches";

$options = [
        "http" => [
                "method" => "GET",
                "header" => "X-Auth-Token: 74457f2a0bfe463ebd96fe50cdb6e02a"
        ]
];

$context = stream_context_create($options);
$json = file_get_contents($url, false, $context);
$data = json_decode($json, true);

// =========================
// SINGLE IMPORT
// =========================
$message = "";

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
            $message = " Match bestaat al in database!";
        }
    } else {
        $message = " Match niet gevonden in API";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Single Match Import</title>

   <link rel="stylesheet" href="../style.css">
</head>

<body>

<header class="header">
    <h1> Import 1 Match</h1>
    <a class="add-btn" href="../match_overview.php">Match</a>
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
            <form method="POST">

                <input type="hidden" name="match_id" value="<?= $match["id"] ?>">

                <button class="btn" type="submit" name="import_one">
                    Import deze match
                </button>

            </form>

        </div>

    <?php endforeach; ?>

</div>

</body>
</html>