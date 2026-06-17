<?php
/*
 * Author: Sagvan
 * Date: 28-5-26
 *  Overzicht van database
 */
require_once "includes/Team.php";
require_once "includes/Database.php";

// Verbinding maken met de database
$database = new Database();
$db = $database->getConnection();

//Teaam class uitvoeren
$team = new Team($db);

$successMessage = "";

if(isset($_POST["update"]))
{
    $team->update(
        $_POST["id"],
        $_POST["name"],
        $_POST["founded"],
        $_POST["clubColors"],
        $_POST["venue"]
    );

    $successMessage = "Team succesvol bijgewerkt.";
}

if(isset($_POST["delete"]))
{
    $team->delete($_POST["id"]);

    $successMessage = "Team succesvol verwijderd.";
}

//$teamsDb["teams"] = $team->readAll();

if (!empty($_GET["search"])) {
    $teamsDb["teams"] = $team->searchTeam($_GET["search"]);
}
else {
    $teamsDb["teams"] = $team->readAll();
}

?>

<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Teams</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">

        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Football App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="ApiTeam.php">API Teams</a></li>
                    <li class="nav-item"><a class="nav-link active" href="DataTeam.php">Database Teams</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container py-5">

        <!-- TITLE -->
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5">Database Teams</h1>
            <p class="text-muted fs-5">Teams in de database</p>
        </div>

        <?php if(!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $successMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        <?php endif; ?>

        <!-- Search -->
        <form method="GET" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Zoek team..."
                               value="<?php echo $_GET['search'] ?? '' ?>">

                        <button class="btn btn-primary">Zoeken</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- CARDS -->
        <div class="row">

            <?php foreach($teamsDb["teams"] as $teamData): ?>
             <!-- MODAL -->
            <div class="modal fade" id="editModal<?php echo htmlspecialchars($teamData["name"]) ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Team Bewerken</h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <?php
                                echo '
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="'. htmlspecialchars($teamData["id"]) .'">
    
                                        <label class="form-label">Naam</label>
                                        <input type="text" name="name" class="form-control mb-3" value="'.htmlspecialchars($teamData["name"]) .'">
    
                                        <label class="form-label">Opgericht</label>
                                        <input type="number" name="founded" class="form-control mb-3" value="'. htmlspecialchars($teamData["founded"]).'">
    
                                        <label class="form-label">Clubkleuren</label>
                                        <input type="text" name="clubColors" class="form-control mb-3" value="'.htmlspecialchars($teamData["clubColors"]).'">
    
                                        <label class="form-label">Stadion</label>
                                        <input type="text" name="venue" class="form-control" value="'.htmlspecialchars($teamData["venue"]).'">
                                    </div>
                                ';
                                ?>
                                <div class="modal-footer">
                                    <button type="submit" name="update" class="btn btn-success">Opslaan</button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>

            <!-- CARD -->
            <div class="col-md-4 mb-4">

                    <div class="card h-100 border-0 shadow-sm">
                        <?php if(!empty($teamData["crest"])): ?>
                            <img src="<?php echo  htmlspecialchars($teamData["crest"]) ?>" class="card-img-top p-3"
                                     style="height:180px; object-fit:contain;">

                        <?php endif; ?>

                    <?php
                        echo '
                            <div class="card-body">
                    
                               <h4 class="card-title fw-bold mb-3">'. htmlspecialchars($teamData["name"]) . '</h4>
                                    <p class="card-text"><strong>Opgericht: </strong>'. htmlspecialchars($teamData["founded"]) .'</p>
                                    <p class="card-text"><strong>Kleuren: </strong>'. htmlspecialchars($teamData["clubColors"]).'</p>
                                    <p class="card-text"><strong>Stadion: </strong>'. htmlspecialchars($teamData["venue"]) .'</p>
                                    <p class="card-text"><strong>Competitie: </strong>'. htmlspecialchars($teamData["competition_name"]).'</p>
        
                               <form method="POST">
                                    <input type="hidden" name="id" value="'. htmlspecialchars($teamData["id"]) .'">
        
                                   <button type="submit" name="delete" class="btn btn-danger w-100 mb-2">Verwijderen</button>
                                </form>
        
                                <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" 
                                        data-bs-target="#editModal'.htmlspecialchars($teamData["id"]) .'">
                                        Bewerken
                                </button>
                            </div>
                        ';
                    ?>
                </div>
        </div>
            <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>