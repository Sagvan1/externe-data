<?php
/*
 * Author: Sagvan
 * Date 28-5-26
 * Overzicht van database
 */


// Klassen inladen (API, database model en database verbinding)
require_once "includes/ApiService.php";
require_once "includes/Competition.php";
require_once "includes/Database.php";

// Database verbinding maken met database "football_app"
$database = new Database();
$db = $database->getConnection();

// Competition-model initialiseren met database verbinding
$competition = new Competition($db);

// Variabele voor succesmeldingen
$successMessage = "";


 // UPDATE
 //Wordt uitgevoerd als op "update" knop is gedrukt
if (isset($_POST["update"]))
{
    $competition->update(
            $_POST["id"],
            $_POST["name"],
            $_POST["code"],
            $_POST["type"]
    );

    $successMessage = "Competition succesvol bijgewerkt.";
}

//DELETE
//Wordt uitgevoerd als op delete knop is gedrukt

if (isset($_POST["delete"]))
{
    $competition->delete($_POST["id"]);

    $successMessage = "Competition succesvol verwijderd.";
}

//Alle competities ophalen uit database
//$competitionsDb["competitions"] = $competition->readAll();

// Zoekfunctie
if (!empty($_GET["search"])) {
        $competitionsDb["competitions"] = $competition->searchCompetition($_GET["search"]);
    }
    else {
        $competitionsDb["competitions"] = $competition->readAll();
    }


?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Competitions</title>

    <!-- Bootstrap CSS voor styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">
    <div class="container">

        <!-- home link -->
        <a class="navbar-brand fw-bold" href="index.php">Football App</a>

        <!-- Hamburger menu mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigatie links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="ApiCompetition.php">API Competitions</a></li>
                <li class="nav-item"><a class="nav-link active" href="DataCompetition.php">Database Competitions</a></li>
            </ul>
        </div>

    </div>
</nav>

<!-- CONTENT -->
<div class="container py-5">

    <!-- Pagina titel -->
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Database Competitions</h1>
        <p class="text-muted fs-5">Competitions in de database</p>
    </div>

    <!-- Succesmelding tonen als die bestaat -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $successMessage ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Zoekformulier -->
    <form method="GET" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="vul naam of code in..."
                           value="<?php echo $_GET['search'] ?? '' ?>">
                    <button class="btn btn-primary">Zoeken</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Cards met alle competities -->
    <div class="row">

        <?php foreach ($competitionsDb["competitions"] as $competition): ?>

        <?php

        echo '
                <!-- MODAL: bewerken van competitie -->
                <div class="modal fade"
                     id="editModal'. $competition["id"]. '"
                     tabindex="-1">
    
                    <div class="modal-dialog">
                        <div class="modal-content">
    
                             <!-- Formulier voor update -->
                             <form method="POST">
                                    
                                 <div class="modal-header">
                                        <h5 class="modal-title">Competition Bewerken</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                 </div>
            
                                 <div class="modal-body">
                                        <!-- verborgen ID veld -->
                                       <input type="hidden" name="id" value="'. $competition["id"] . '">
                                            
                                       <label class="form-label">Naam</label>
                                       <input type="text" name="name" class="form-control mb-3" value="'. $competition["name"] . '">   
                                       <label class="form-label">Code</label>
                                       <input type="text" name="code" class="form-control mb-3" value="'. $competition["code"]. '">
                                       <label class="form-label">Type</label>
                                       <input type="text" name="type" class="form-control" value="' . $competition["type"] . '">
                                 </div>
                                        
                                 <div class="modal-footer">
                                        <button type="submit" name="update" class="btn btn-success">
                                                Opslaan
                                        </button>
                                 </div>
                             </form>
                        </div>
                    </div>
                </div>
            
            ';
        ?>


            <!-- CARD per competitie -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">

                    <!-- Embleem tonen als die bestaat -->
                    <?php if (!empty($competition["emblem"])): ?>
                        <img src="<?php echo  $competition["emblem"] ?>"
                             class="card-img-top p-3"
                             style="height: 180px; object-fit: contain;">

                    <?php endif; ?>

                    <?php

                    echo '

                    <div class="card-body">

                        <!-- Competitie info -->
                        <h4 class="card-title fw-bold mb-3">
                            '. $competition["name"] . '
                        </h4>

                        <p class="card-text">
                            <strong>Code:</strong> '. $competition["code"] .'</p>

                        <p class="card-text">
                            <strong>Type:</strong> '. $competition["type"] . ' </p>

                        <!-- DELETE + EDIT knoppen -->
                        <form method="POST" onsubmit="return confirm(\'Weet je zeker dat je dit competitie wilt verwijderen?\');">

                            <input type="hidden" name="id" value="'. $competition["id"] .'">
                            <!-- Delete knop -->
                            <button type="submit" name="delete" class="btn btn-danger w-100 mb-2">
                                Verwijderen
                            </button>

                        </form>

                        <!-- Edit knop opent modal -->
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                data-bs-target="#editModal'. $competition["id"] . '">
                            Bewerken
                        </button>

                    </div>
                </div>
            </div>
            ';
        ?>

        <?php endforeach; ?>

    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>