<?php


/*
 * controler de attaquer.php
 */


include "library/init.php";
$idAdversaire = isset($_GET["id"]) ? $_GET["id"] : "";
$player = new Player($_SESSION['id']);




$resultat = $player->attack($idAdversaire);

if($resultat === "EQUAL"){
    echo "egaliter";
}
if($resultat === "LOOSE"){
    echo "perdu";
}
if($resultat === "WIN"){
    echo "gagné";
}

