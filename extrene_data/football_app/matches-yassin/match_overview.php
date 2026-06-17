<?php
require_once "includes/function.php";
require_once "includes/football.php";

// DB connectie
$database = new Database();
$db = $database->getConnection();

$matchClass = new MatchFootball($db);

if (isset($_POST["delete"])) {
    $matchClass->delete((int)$_POST["id"]);

    header("Location: match_overview.php");
    exit;
}

// =========================
// ZOEKEN OF ALLES OPHALEN
// =========================
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);


    $matches = $matchClass->searchFootball($search);

} else {

    $matches = $matchClass->readAll();
}
session_start();





?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Match Overview</title>

    <!-- CSS -->
    <link rel="stylesheet" href="includes/style.css">

</head>

<body>


<header class="header">
    <h1>Match Overview</h1>
    <a class="add-btn" href="includes/Database.php">Toevoegen</a>
</header>

<form method="GET" action="match_overview.php">

    <?php
        //wat je zoekt komt in de place holder
        if (isset($_GET['search']) && !empty($_GET['search']))
        {
            echo "<input type='text' name='search' placeholder=" . $_GET["search"] . ">";
        } else {
            //als er niks in de zoekveld zit dan veranderd de placeholder naar de default
          echo "<input type='text' name='search' placeholder='vul teamnaam in...'>";
        }
    ?>
    <input type="submit" value="Zoeken">
</form>



<?php
    //als geen data hebt van wat je zoekt geeft hij geen resultaten aan
    if (sizeof($matches) < 1) {
        echo "Geen resultaten gevonden";
    }

    foreach ($matches as $match): ?>

    <div class="card">

        <h2>
            <?= htmlspecialchars($match["homeTeam"]) ?>
            vs
            <?= htmlspecialchars($match["awayTeam"]) ?>
        </h2>

        <p><strong>ID:</strong> <?= htmlspecialchars($match["id"]) ?></p>
        <p><strong>Datum:</strong> <?= htmlspecialchars($match["utcDate"]) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($match["status"]) ?></p>
        <p><strong>Matchday:</strong> <?= htmlspecialchars($match["matchday"]) ?></p>
        <p><strong>Competition:</strong> <?= htmlspecialchars($match["competition"]) ?></p>

        <!-- UPDATE KNOP -->
        <a href="update.php?id=<?= $match["id"] ?>" class="btn-update">
            Update
        </a>

        <!-- DELETE KNOP -->
        <form method="POST" style="display:inline;">
            <input
                    type="hidden"
                    name="id"
                    value="<?= $match["id"] ?>"
            >

            <button
                    type="submit"
                    name="delete"
                    class="btn-delete"
                    onclick="return confirm('Weet je zeker dat je deze match wilt verwijderen?')"
            >
                Delete
            </button>
        </form>

    </div>

<?php endforeach; ?>


</body>
</html>