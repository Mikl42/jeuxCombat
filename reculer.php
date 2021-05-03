<?php

/* 
 * controler reculer
 *  appeler par ajax depuis plateauJeu
 */

include "library/init.php";

//recuperation des parametres: 
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";


$player = new Player($id);


if ($player->get("room")>=1){
    $player->set(get('room'), $player->get('room')-1);

    $player->update();
    include "templates/pages/plateauJeu.php";
}
