<?php

/**
 * Controller afficher_form_crea_perso.php
 * Role :  affiche le formulaire de création du personnage (templates/pages/form_creer_perso.php)
 * Paramètre : néant
 */

// Inclusion du init
include 'library/init.php';


$player = new Player();
if (isset($_REQUEST)) {
    $player->loadFromPost($_REQUEST);
    $player =  $player->playerExist();
    if (!$player){
        header("location: index.php");
    }
    // On enregistre l id du player dans une session id
    $_SESSION['id'] = $player->getId();
    // On récupère la liste de tous les joueurs qui sont dans la meme pièce
    $listePlayer = $player->listePlayerSameRoom();
    include 'templates/pages/plateauJeu.php';
}
