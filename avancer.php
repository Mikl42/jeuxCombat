<?php

/* 
 * controler avancer 
 * consomme des points d'agilité : il faut autant de
points d'agilité que le numéro de la pièce à atteindre
 * 
 * Si on n'a pas assez de points d'agilité, on n'a pas accès à la pièce suivante
 */

include "library/init.php";


//recupere le nombre de point d'agilité du joueur
//recupere le numero de la piece où se situe le joueur
//si le nombre d'agilité est >=  (au numero de la piece) +1 alors on avance dans la piece suivante.
//update room +1



$player = new Player($_SESSION['id']);


$player->avancer();
//retour au template avec MAJ de la zone room
include "templates/fragments/roomPrincipal.php";  




