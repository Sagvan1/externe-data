<?php
/*
 * Author: Sagvan Alfatah
 * Date: 22-5-26
 * Overzicht van API
 */

// Inladen van de classes
require_once "includes/ApiService.php";
require_once "includes/Competition.php";
require_once "includes/Database.php";

// Verbinding maken met de database
$database = new Database();
$db = $database->getConnection();

//Competition Class uitvoeren
$competition = new Competition($db);

//api class uitvoeren
$apiService = new ApiService();

// Competities ophalen uit de Football API
$competitionsAPI = $apiService->getDataFromApi("competitions");

// Variabele voor succesmelding
$successMessage = "";

// Controleren of het formulier is verzonden
if (isset($_POST["save"]))
    {
        // Gegevens uit het formulier verzamelen
        $params = [];

        $params["id"] = $_POST["id"];
        $params["name"] = $_POST["name"];
        $params["code"] = $_POST["code"];
        $params["type"] = $_POST["type"];
        $params["emblem"] = $_POST["emblem"];

        // Create functie uit de Competition-class wordt uitgevoerd
        // Competition opslaan in de database
        $competition->create($params);

        // Succesmelding tonen
        $successMessage = $params["name"] . " is opgeslagen in de database.";
    }

?>

<!DOCTYPE html>
<html lang="nl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>API Competitions</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

<body class="bg-light">

<!-- Navigatiemenu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">

    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Football App</a>

        <!-- Hamburger-menu voor mobiele schermen -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigatielinks -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="ApiCompetition.php">API Competitions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="DataCompetition.php">Database Competitions</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hoofdinhoud -->
<div class="container py-5">

    <!-- Paginatitel -->
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">API Competitions</h1>
        <p class="text-muted fs-5">Competitions vanuit de Football API</p>
    </div>

    <!-- Succesmelding tonen na opslaan -->
     <!-- Is er een melding? -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3">

            <?php echo $successMessage ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Zoekformulier -->
    <form method="GET" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="vul competition in..."
                           value="<?php echo $_GET['search'] ?? '' ?>">
                    <button class="btn btn-primary">Zoeken</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Competitiekaarten -->
    <div class="row">

        <!-- Alleen uitvoeren als de API competities heeft teruggegeven -->
        <?php if(isset($competitionsAPI["competitions"])): ?>

        <?php foreach ($competitionsAPI["competitions"] as $competition):?>

            <?php

                // Zoekfilter toepassen indien zoekterm is ingevuld
                if (!empty($_GET["search"]))
                {
                    // Competitie overslaan als de naam niet overeenkomt
                    if (stripos($competition["name"], $_GET["search"]) === false)
                    {
                        continue;
                    }
                }

            ?>

            <!-- Competition kaart -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">

                    <!-- Logo van de competition tonen -->
                    <?php if (!empty($competition["emblem"])): ?>

                        <img src="<?php echo $competition["emblem"]; ?>" class="card-img-top p-3"
                             style="height: 180px; object-fit: contain;">

                    <?php endif; ?>

                    <div class="card-body">

                        <?php

                         echo '<!-- Naam van de competition -->
                                <h4 class="card-title fw-bold mb-3">'. $competition["name"] . '</h4>
                                
                                <!-- Code van de competition -->
                                <p class="card-text"><strong>Code: </strong>' . $competition["code"] . '</p>
                                <!-- Type van de competition -->
                                <p class="card-text"><strong>Type: </strong>'. $competition["type"] . '</p>
                                
                                <!-- Formulier om competition op te slaan -->
                                <form method="POST" class="mt-3">
      
                                    <!-- Verborgen velden met competition gegevens -->
                                    <input type="hidden" name="id" value="' . $competition["id"] . '">
                                    <input type="hidden" name="name" value="'. $competition["name"] . '">
                                    <input type="hidden" name="code" value="'. $competition["code"] . '">
                                    <input type="hidden" name="type" value="'. $competition["type"] . '">
                                    <input type="hidden" name="emblem" value="'. $competition["emblem"] . '">
        
                                    <!-- Opslaan-knop -->
                                    <button type="submit" name="save" class="btn btn-success w-100">
                                        Toevoegen aan database
                                    </button>
       
                                </form>
                                        
                             ';
                         ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>