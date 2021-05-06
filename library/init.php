<?php

/* 
 * Initialisatons commune à tous les programmes (fonctionne en l'incluant dans les controleurs)
 */
session_start();

// Debug
ini_set('display_errors', true);
error_reporting(E_ALL);

// Ouverture de la base de données
global $bdd; // On déclare une variable $bdd pour ouvrir la base de données, globale, c.a.d accessible dans les fonctions et méthodes
$bdd = new PDO("mysql:host=localhost;dbname=projets_combat_wsangle", "wsangle", "S6raV?8t%K");

// Pour faciliter le debuggage :
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// Inclure les calsses générales
include_once "model/_model.php";

// Inclure les classes de model de données
include_once "model/Player.php";
include_once "model/Historic.php";

