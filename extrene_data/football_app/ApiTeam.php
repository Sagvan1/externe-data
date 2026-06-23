<?php
/*
 * Author: Sagvan
 * Date 28-5-26
 * Overzicht van API
 */
// inladen van classes
require_once "includes/ApiService.php";
require_once "includes/Team.php";
require_once "includes/Database.php";

// Verbinding maken met de database
$database = new Database();
$db = $database->getConnection();

//Teaam class uitvoeren
$team = new Team($db);

//API class uitvoeren
$apiService = new ApiService();

//Teams ophalen uit de football API
$teamsAPI = $apiService->getDataFromApi("competitions/DED/teams");

// Variabele voor succesmelding
$successMessage = "";

// Controleren of het formulier is verzonden
if (isset($_POST["save"]))
{
    // Gegevens uit het formulier verzamelen
    $params = [];

    $params["id"] = $_POST["id"];
    $params["name"] = $_POST["name"];
    $params["crest"] = $_POST["crest"];
    $params["founded"] = $_POST["founded"];
    $params["clubColors"] = $_POST["clubColors"];
    $params["venue"] = $_POST["venue"];
    $params["competition_id"] = $_POST["competition_id"];

    // teams opslaan in de database.
    $team->create($params);

    // Success melding tonen
    $successMessage = $params["name"] . " is opgeslagen in de database.";
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Teams</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">

        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Football App</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="ApiTeam.php">API Teams</a></li>

                    <li class="nav-item"><a class="nav-link" href="DataTeam.php">Database Teams</a></li>
                </ul>

            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-5">

        <!-- Title -->
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5">API Teams</h1>
            <p class="text-muted fs-5">Teams vanuit de Football API</p>
        </div>

        <!-- success message  -->
        <?php if (!empty($successMessage)): ?>

            <div class="alert alert-success alert-dismissible fade show mt-3">

                <?php echo $successMessage ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        <?php endif; ?>

        <!-- Search -->
        <form method="GET" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder=" Zoek team..."
                               value="<?php echo $_GET['search'] ?? '' ?>">

                        <button class="btn btn-primary">Zoeken</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- cards -->
        <div class="row">

            <!-- Alleen uitvoeren als de API competities heeft teruggegeven -->
            <?php if (isset($teamsAPI["teams"])): ?>

                <?php foreach ($teamsAPI["teams"] as $teamData): ?>

                    <?php

                        // Zoekfilter toepassen indien zoekterm is ingevuld
                        if (!empty($_GET["search"]))
                        {
                            // Competitie overslaan als de naam niet overeenkomt
                            if (stripos($teamData["name"], $_GET["search"]) === false)
                            {
                                continue;
                            }
                        }

                    ?>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">

                            <!-- Logo van de competition tonen -->
                            <?php if (!empty($teamData["crest"])): ?>

                                <img src="<?php echo $teamData["crest"] ?> "
                                class="card-img-top p-3"
                                         style="height: 180px; object-fit: contain;">
    
                            <?php endif; ?>

                            <?php

                                echo '
                                       <div class="card-body">
                                              <!-- Naam van de team-->
                                            <h4 class="card-title fw-bold mb-3">'.  $teamData["name"]. '</h4>
                                            <!-- Opgericht van de team-->
                                            <p class="card-text"><strong>Opgericht: </strong>'. $teamData["founded"] . '</p>
                                            <!-- Kleur van de team-->
                                            <p class="card-text"><strong>Kleuren: </strong>'. $teamData["clubColors"].'</p>
                                            <!-- Stadion van de team-->
                                            <p class="card-text"><strong>Stadion: </strong>'. $teamData["venue"].'</p>
            
                                            <!-- Formulier om team op te slaan -->
                                            <form method="POST" class="mt-3">
                                            
                                                <!-- Verborgen velden met teams gegevens -->
                                                <input type="hidden" name="id" value="'. $teamData["id"] .'">
                                                <input type="hidden" name="name" value="'. $teamData["name"] . '">
                                                <input type="hidden" name="crest" value="'. $teamData["crest"].'">
                                                <input type="hidden" name="founded" value="'. $teamData["founded"] .'">
                                                <input type="hidden" name="clubColors" value="'. $teamData["clubColors"].'">
                                                <input type="hidden" name="venue" value="'. $teamData["venue"] .'">
                                                <input type="hidden" name="competition_id" value="'. $teamData['runningCompetitions'][0]["id"] .'">
                                                
                                              
                                                 <!-- Opslaan-knop -->
                                                <button type="submit" name="save" class="btn btn-success w-100">
                                                    Toevoegen aan database
                                                </button>
                                            </form>
                                       </div>
                                ';
                            ?>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>

<!-- Bootstrap  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>