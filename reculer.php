<?php

/* 
 * controler reculer
 *  appeler par ajax depuis plateauJeu
 */

include "library/init.php";



$player = new Player($_SESSION['id']);


$player->reculer();
//retour au template avec MAJ de la zone room
include "templates/fragments/roomPrincipal.php";  