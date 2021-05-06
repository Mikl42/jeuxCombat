<?php

/* 
 *controler enregistrement nouveau perso
 *  template amont: form_creer_perso.php
 *  parametre : POST du form
 */


// Inclusion du init
include 'library/init.php';

// Instancie un nouvel objet Personnage

$player = new Player();
if (isset($_REQUEST)){
    $_REQUEST['pwd'] = password_hash($_REQUEST['pwd'], PASSWORD_DEFAULT);
    $player->loadFromPost($_REQUEST);
    $player->create();  
    include "templates/pages/accueil.php";
}
