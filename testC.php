<?php


/*
 * controler de test
 */


include "library/init.php";

$idAdversaire = $_GET['id'];

$player = new Player($_SESSION['id']);

$player->resultatCombat($player->attack($idAdversaire));

if($resultat === "EQUAL"){
    echo "egaliter";
}
if($resultat === "LOOSE"){
    echo "perdu";
}
if($resultat === "WIN"){
    echo "gagné";
}


