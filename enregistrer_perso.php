<?php
/**
 * Controller : enregistrer_perso.php
 * Role : Enregistre le personnage dans la base de donnée
 * Paramètre : $_REQUEST : pseudo, pwd, strength, agility, resistance
 * Retour : Néant
 */

// Inclusion du init
include 'library/init.php';

// Instancie un nouvel objet Personnage

$perso = new Player();
if (isset($_REQUEST)){

    $_REQUEST['pwd'] = password_hash($_REQUEST['pwd'], PASSWORD_DEFAULT);
    $perso->loadFromPost($_REQUEST);
    if(!$perso->pseudoExist()){
        $perso->create();
        header("location: index.php");
        exit();
    }
    header("location: afficher_form_crea_perso.php");
}