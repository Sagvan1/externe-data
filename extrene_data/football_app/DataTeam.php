<?php
/*
 * Author: Sagvan
 * Date: 28-5-26
 * Overzicht van database
 */

// Inladen van classes
require_once "includes/Team.php";
require_once "includes/Database.php";

// Verbinding maken met de database
$database = new Database();
$db = $database->getConnection();

// Team-object aanmaken zodat teamfuncties gebruikt kunnen worden.
$team = new Team($db);

// Variabele voor succesmeldingen na acties
$successMessage = "";

// Controleren of er een updateformulier is verstuurd
if(isset($_POST["update"]))
{
    // Teamgegevens bijwerken in de database
    $team->update(
            $_POST["id"],
            $_POST["name"],
            $_POST["founded"],
            $_POST["clubColors"],
            $_POST["venue"]
    );

    // Succesmelding tonen
    $successMessage = "Team succesvol bijgewerkt.";
}

// Controleren of er op verwijderen is geklikt
if(isset($_POST["delete"]))
{
    // Team verwijderen uit de database
    $team->delete($_POST["id"]);

    // Succesmelding tonen
    $successMessage = "Team succesvol verwijderd.";
}


// Controleren of er een zoekopdracht is ingevuld
if (!empty($_GET["search"])) {

    // Teams ophalen die overeenkomen met de zoekterm
    $teamsDb["teams"] = $team->searchTeam($_GET["search"]);
}
else {

    // Geen zoekopdracht, dus alle teams ophalen
    $teamsDb["teams"] = $team->readAll();
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">

    <!-- Zorgt ervoor dat de pagina goed schaalt op mobiele apparaten -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Database Teams</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">

    <div class="container">

        <!-- Logo / Titel -->
        <a class="navbar-brand fw-bold" href="index.php">Football App</a>

        <!-- Hamburger menu voor mobiele schermen -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigatielinks -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="ApiTeam.php">API Teams</a></li>
                <li class="nav-item"><a class="nav-link active" href="DataTeam.php">Database Teams</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HOOFDINHOUD -->
<div class="container py-5">

    <!-- Titel van de pagina -->
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Database Teams</h1>
        <p class="text-muted fs-5">Teams in de database</p>
    </div>

    <!-- Succesmelding tonen indien aanwezig -->
    <?php if(!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $successMessage ?>

            <!-- Knop om melding te sluiten -->
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

    <!-- Zoekformulier -->
    <form method="GET" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="input-group">

                    <!-- Zoekveld -->
                    <input type="text" name="search" class="form-control"
                           placeholder="Zoek team..."
                           value="<?php echo $_GET['search'] ?? '' ?>">

                    <!-- Zoekknop -->
                    <button class="btn btn-primary">Zoeken</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Teamkaarten -->
    <div class="row">

        <!-- Door alle teams heen lopen -->
        <?php foreach($teamsDb["teams"] as $teamData): ?>

            <!-- MODAL VOOR BEWERKEN -->
            <div class="modal fade" id="editModal<?php echo $teamData["id"] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Formulier voor aanpassen van teamgegevens -->
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Team Bewerken</h5>

                                <!-- Modal sluiten -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <?php
                            echo '
                                    <div class="modal-body">

                                        <!-- Verborgen ID zodat bekend is welk team wordt aangepast -->
                                        <input type="hidden" name="id" value="'. $teamData["id"] .'">

                                        <!-- Naam van team -->
                                        <label class="form-label">Naam</label>
                                        <input type="text" name="name" class="form-control mb-3" value="'.$teamData["name"] .'">

                                        <!-- Oprichtingsjaar -->
                                        <label class="form-label">Opgericht</label>
                                        <input type="number" name="founded" class="form-control mb-3" value="'. $teamData["founded"].'">

                                        <!-- Clubkleuren -->
                                        <label class="form-label">Clubkleuren</label>
                                        <input type="text" name="clubColors" class="form-control mb-3" value="'.$teamData["clubColors"].'">

                                        <!-- Stadion -->
                                        <label class="form-label">Stadion</label>
                                        <input type="text" name="venue" class="form-control" value="'.$teamData["venue"].'">
                                    </div>
                                ';
                            ?>

                            <div class="modal-footer">

                                <!-- Opslaan knop -->
                                <button type="submit" name="update" class="btn btn-success">
                                    Opslaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TEAM CARD -->
            <div class="col-md-4 mb-4">

                <div class="card h-100 border-0 shadow-sm">

                    <!-- Clublogo tonen indien aanwezig -->
                    <?php if(!empty($teamData["crest"])): ?>
                        <img src="<?php echo  $teamData["crest"] ?>"
                             class="card-img-top p-3"
                             style="height:180px; object-fit:contain;">
                    <?php endif; ?>

                    <?php
                    echo '
                            <div class="card-body">

                               <!-- Teamnaam -->
                               <h4 class="card-title fw-bold mb-3">'. $teamData["name"] . '</h4>

                                    <!-- Teaminformatie -->
                                    <p class="card-text"><strong>Opgericht: </strong>'. $teamData["founded"] .'</p>
                                    <p class="card-text"><strong>Kleuren: </strong>'. $teamData["clubColors"].'</p>
                                    <p class="card-text"><strong>Stadion: </strong>'. $teamData["venue"] .'</p>
                                    <p class="card-text"><strong>Competitie: </strong>'. $teamData["competition_name"].'</p>

                               <!-- Formulier voor verwijderen -->
                               <form method="POST"
                                     onsubmit="return confirm(\'Weet je zeker dat je dit team wilt verwijderen?\');">

                                    <!-- Verborgen ID -->
                                    <input type="hidden" name="id" value="'. $teamData["id"] .'">

                                   <!-- Verwijderknop -->
                                   <button type="submit" name="delete"
                                           class="btn btn-danger w-100 mb-2">
                                           Verwijderen
                                   </button>
                                </form>

                                <!-- Knop om bewerkvenster te openen -->
                                <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                        data-bs-target="#editModal'.$teamData["id"] .'">
                                        Bewerken
                                </button>
                            </div>
                        ';
                    ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>