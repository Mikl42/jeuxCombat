<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Inclusion du init
include 'library/init.php';


$player = new Player();

if (isset($_REQUEST)) {
    $player->loadFromPost($_REQUEST);
    $player = $player->playerExist();
    if (!$player) {
        header("location: index.php");
    }
    $_SESSION['id'] = $player->getId();
    // $_GET['id'] = $_SESSION['id'];
    $listePlayer = $player->listePlayerSameRoom();


//recupere tous les stat
    $agility = $player->get("agility");
    $room = $player->get("room");
    $resistance = $player->get("resistance");
    $strength = $player->get("strength");
    $hp = $player->get("hp");

    $listeHistoricPlayer = $player->loadHistoric();
    include 'templates/pages/plateauJeu.php';
}

