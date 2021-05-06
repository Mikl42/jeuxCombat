<?php

/*
 * controler augmenter_res.php
 * appelé depuis le template plateuJeu.php par l'ajax
 * recoit l'id du player
 * verifie les données force agilité et resistance si possibilité de modification
 * si oui modification des element
 * sinon retour aux template avec messageAgilityDefaut
 */

include "library/init.php";


$player = new Player($_SESSION['id']);


$message = $player->augmenterResistance();

//retour au template plateauJeu en passant par ajax pour la MAJ des stat
include "templates/fragments/player.php";
   

