<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * Home page van de website
 */

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .hero {
            position: relative;
            height: 85vh;
            overflow: hidden;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(55%);
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="ApiCompetition.php">Competitions API</a></li>
                <li class="nav-item"><a class="nav-link" href="ApiTeam.php">Teams API</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Players API</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Matches API</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Standings API</a></li>
            </ul>

        </div>

    </div>
</nav>

<!-- HERO IMAGE -->
<div class="hero">
    <img src="images/santiago.jpg" alt="Stadium">

    <div class="hero-text">
        <h1 class="fw-bold display-3">Football App</h1>
        <p class="fs-4">Gebruik de navigatie om API data en database data te bekijken.</p>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>