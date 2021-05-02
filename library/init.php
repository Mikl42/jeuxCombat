<?php

/*
 * Initialisatons commune à tous les programmes
 */

//je créer une variable session de connection
session_start();

//report des erreurs

ini_set('display_errors', true);
error_reporting(E_ALL);

//connection à la base de données
global $bdd;
$bdd = new PDO("mysql:host=localhost;dbname=projets_combat_wsangle", "wsangle", "S6raV?8t%K");

//Pour le debuggage
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);



// Inclure les classes de model de données
include "models/Player.php";