<?php

/* 
 * controler actualiser_plateauJeu.php
 * role : affiche 
 * parametre : neant
 * 
 */
include 'library/init.php';

$player = new Player($_SESSION['id']);
$listePlayer = $player->listePlayerSameRoom();
//recupere toutes les stats
    $agility = $player->get("agility");
    $room = $player->get("room");
    $resistance = $player->get("resistance");
    $strength = $player->get("strength");
    $hp = $player->get("hp");
    

include "templates/fragments/main.php";