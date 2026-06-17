<?php

require_once "Verlichting.php";
require_once "tuin_verlichting.php";
require_once "DiscoController.php";

$verlichting = new Verlichting();

$tuin_verlichting = new Tuin_verlichting();

$disco = new DiscoController();


$kleur = "uit";

if (isset($_POST["knop"])) {
    if ($_POST["knop"] == "wit") {
        $kleur = $verlichting->witteLed();
    }

    if ($_POST["knop"] == "rood") {
        $kleur = $verlichting->rodeLed();
    }

    if ($_POST["knop"] == "blauw") {
        $kleur = $verlichting->blauweLed();
    }

    if ($_POST["knop"] == "paars") {
        $kleur = $tuin_verlichting->paarseTuinverlichting();
    }

    if ($_POST["knop"] == "geel") {
        $kleur = $verlichting->geelLed();
    }

    if ($_POST["knop"] == "relax") {
        $kleur = $verlichting->relaxstand();
    }

    if ($_POST["knop"] == "feest"){
        $kleur = $verlichting->feestSagvan();
    }
    if ($_POST["knop"] == "alarm"){
        $kleur = $verlichting->alarmstand();
    }


    if ($_POST["knop"] == "uit") {
        $kleur = $verlichting->allesUit();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Afstandsbediening verlichting</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #777;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .afstandsbediening {
            width: 40%;
            padding: 40px;
            background-color: #555;
            color: white;
        }

        .lampen {
            width: 60%;
            padding: 40px;
            background-color: #888;
            display: flex;
            gap: 30px;
            justify-content: center;
            align-items: center;
        }

        h1 {
            margin-top: 0;
        }

        button {
            display: block;
            width: 250px;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .lamp {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #333;
            border: 5px solid #222;
            box-shadow: none;
        }

        .lampje {
            text-align: center;
            color: white;
            font-weight: bold;
        }

        .wit-aan {
            background-color: white;
            box-shadow: 0 0 30px white;
        }

        .rood-aan {
            background-color: red;
            box-shadow: 0 0 30px red;
        }

        .blauw-aan {
            background-color: blue;
            box-shadow: 0 0 30px blue;
        }
        .geel-aan {
            background-color: yellow;
            box-shadow: 0 0 30px yellow;
        }

        .paars-aan {
            background-color: purple;
            box-shadow: 0 0 30px purple;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="afstandsbediening">
        <h1>Afstandsbediening</h1>

        <form method="post">
            <button type="submit" name="knop" value="wit">Witte led aan</button>
            <button type="submit" name="knop" value="rood">Rode led aan</button>
            <button type="submit" name="knop" value="blauw">Blauwe led aan</button>
            <button type="submit" name="knop" value="geel">Geel led aan</button>
            <button type="submit" name="knop" value="paars">Paarse tuinverlichting aan
            <button type="submit" name="knop" value="relax">Relaxstand</button>
            <button type="submit" name="knop" value="feest">Feeststand</button>
             <button type="submit" name="knop" value="alarm">Alarmstand</button>
            <button type="submit" name="knop" value="uit">Alles uit</button>
        </form>
    </div>

    <div class="lampen">

        <div class="lampje">
            <div class="lamp  <?php if ($kleur == "wit" || $kleur == "relax") echo "wit-aan"; ?> "></div>
            <p>Wit</p>
        </div>

        <div class="lampje">
            <div class="lamp <?php if ($kleur == "rood" || $kleur == "feest" || $kleur == "alarm") echo "rood-aan"; ?>"></div>

            <p>Rood</p>
        </div>

        <div class="lampje">
            <div class="lamp <?php if ($kleur == "blauw" || $kleur == "feest") echo "blauw-aan"; ?>"></div>
            <p>Blauw</p>
        </div>

        <div class="lampje">
            <div class="lamp <?php if ($kleur == "geel" || $kleur == "relax") echo "geel-aan"; ?>"></div>
            <p>Geel</p>
        </div>

        <div class="lampje">
            <div class="lamp  <?php  if ($kleur == "paars" || $kleur == "feest") echo "paars-aan"; ?> "></div>
            <p>Tuin</p>
        </div>


    </div>

</div>

</body>
</html>