<?php

/*
 * controler augmenter_force.php
 * appelé depuis le template plateuJeu.php par l'ajax
 * recoit l'id du player
 * verifie les données force agilité et resistance si possibilité de modification
 * si oui modification des element
 * sinon retour aux template avec messageAgilityDefaut
 */

include "library/init.php";

//recuperation des parametres: 
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";


$player = new Player($id);

//si agility est strictement superieur a 3 et la force <= a 14 alors et resistance strictement sup a 1.
if (($player->get('agility') > 3) && ($player->get('strength') <= 14) && ($player->get('resistance') > 1)) {
    //agility perd 3 point, force gagne 1 pt et resistance perd 1pt
    $player->set("agility", $player->get('agility') - 3);
    $player->set("strength", $player->get('strength') + 1);
    $player->set("resistance", $player->get('resistance') - 1);

//appel la methode qui mettra a jour les stat
    $player->update();
//retour au template plateauJeu en passant par ajax pour la MAJ des stat
    include "templates/fragments/player.php";
}

//sinon si point d'agi insuffisant
else if ($player->get('agility') <= 3) {
    $message = "agilité insuffisante";
    //retour au template plateauJeu en passant par ajax pour la MAJ des stat
    include "templates/pages/plateauJeu.php";
}
//sinon si force deja trop elevée
else if ($player->get('strength') >= 15) {
    $message = "point de force au maximum";
    //retour au template plateauJeu en passant par ajax pour la MAJ des stat
    include "templates/pages/plateauJeu.php";
}
//sinon si resistance insuffisante
else if ($player->get('resistance') <= 1) {
    $message = "point de resistance insuffisant";
    //retour au template plateauJeu en passant par ajax pour la MAJ des stat
    include "templates/pages/plateauJeu.php";
}

