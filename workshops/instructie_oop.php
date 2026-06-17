<?php
/*
 * Author: Sagvan Alfatah
 * Date: 22-4-2026
 * Insturctie OOP
 */

class Auto {

    // properties (eigenschappen van een aauto)
    public $merk;
    public $kleur;
    public $bouwjaar;

    //construct wordt automatiscch uitgevoerd bij het maken van een object
    public function __construct($merk, $kleur, $bouwjaar )
    {
        $this->merk = $merk;
        $this->kleur = $kleur;
        $this->bouwjaar = $bouwjaar;
    }

    //functie binnen class > Method
    public function toonInfo() {
        return $this->merk . "," . $this->merk . "," . $this->bouwjaar;
    }
}


$auto1 = new Auto("BMW", "Zwart", "2020");
$auto2 = new Auto ("Audi", "Groen", "2025");
$auto3 = new Auto("Skoda", "Rood", "2007");

//echo $auto1->toonInfo();
//array
$autos = [$auto1, $auto2, $auto3];

foreach($autos as $auto)
{
    echo $auto->toonInfo();
    echo "<br>";
}